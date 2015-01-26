<?php

namespace Detail\FileConversion\Processing\Adapter;

use Detail\FileConversion\Client\FileConversionClient;
use Detail\FileConversion\Client\Response\Job;

use Detail\FileConversion\Processing\Task;
use Detail\FileConversion\Processing\Exception;

class InternalAdapter extends BaseAdapter
{
//    const OPTION_NOTIFICATION_URL = 'notification_url';

    /**
     * @var FileConversionClient
     */
    protected $client;

    /**
     * @var InternalJobCreatorInterface
     */
    protected $jobCreator;

    /**
     * @param FileConversionClient $client
     * @param InternalJobCreatorInterface $jobCreator
     * @param array $options
     */
    public function __construct(
        FileConversionClient $client, InternalJobCreatorInterface $jobCreator, array $options = array()
    ) {
        parent::__construct($options);

        $this->setClient($client);
        $this->setJobCreator($jobCreator);
    }

    /**
     * @return FileConversionClient
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param FileConversionClient $client
     */
    public function setClient(FileConversionClient $client)
    {
        $this->client = $client;
    }

    /**
     * @return InternalJobCreatorInterface
     */
    public function getJobCreator()
    {
        return $this->jobCreator;
    }

    /**
     * @param InternalJobCreatorInterface $jobCreator
     */
    public function setJobCreator(InternalJobCreatorInterface $jobCreator)
    {
        $this->jobCreator = $jobCreator;
    }

    /**
     * @param Task\TaskInterface $task
     * @return string Process identifier
     */
    public function startProcessing(Task\TaskInterface $task)
    {
        $job = $this->createJob($task);

        $client = $this->getClient();

        try {
            $response = $client->submitJob($job);
            return $response->getId();

        } catch (\Exception $e) {
            throw new Exception\RuntimeException(
                sprintf('DWS FileConversion API request failed: %s', $e->getMessage()),
                0,
                $e
            );
        }
    }

    /**
     * @param Task\TaskInterface $task
     * @return Task\ResultInterface|null
     */
    public function checkProcessing(Task\TaskInterface $task)
    {
        $client = $this->getClient();

        try {
            $job = $client->fetchJob(array('job_id' => $task->getProcessId()));

        } catch (\Exception $e) {
            throw new Exception\RuntimeException(
                sprintf('DWS FileConversion API request failed: %s', $e->getMessage()),
                0,
                $e
            );
        }

        // End directly when job is in a final state
        if (in_array($job->getStatus(), array('complete', 'error'))) {
            return $this->endProcessing($task, $job);
        }

        return null;
    }

    /**
     * @param Task\TaskInterface $task
     * @param Job|array $job
     * @return Task\ResultInterface
     */
    public function endProcessing(Task\TaskInterface $task, $job)
    {
        if (is_array($job)) {
            $job = $this->getJobFromResponse($job);
        } else if (!$job instanceof Job) {
            throw new Exception\RuntimeException(
                'Invalid response; expected array or Detail\FileConversion\Client\Response\Job object'
            );
        }

        // Consider everything but error states as success
        if (!in_array($job->getStatus(), array('error', 'error_notifying'))) {
            $outputs = array();

            foreach ($job->getResults() as $result) {
                $outputs[] = new Task\Output(
                    $result->getIdentifier(),
                    $result->getUrl(),
                    $result->getMeta()
                );
            }

            /** @todo We should probably fail when there are no outputs... */

            $result = new Task\SuccessResult($task, $outputs, $job->getSourceMeta());
        } else {
            $result = new Task\ErrorResult($task, 'Job failed'); /** @todo Provide specific error message */
        }

        return $result;
    }

    /**
     * @param Task\TaskInterface $task
     * @return \Detail\FileConversion\Client\Job\Definition\JobDefinitionInterface
     */
    protected function createJob(Task\TaskInterface $task)
    {
        $jobCreator = $this->getJobCreator();

        if ($jobCreator === null) {
            throw new Exception\RuntimeException(
                'Job creator is required to create DWS FileConversion jobs'
            );
        }

        return $jobCreator->create($task);
    }

    /**
     * @param array $response
     * @return Job
     */
    protected function getJobFromResponse(array $response)
    {
        return new Job($response);
    }
}

<?php

namespace Detail\FileConversion\Processing\Adapter\Internal;

use Detail\FileConversion\Client\Exception as ClientException;
use Detail\FileConversion\Client\FileConversionClient;
use Detail\FileConversion\Client\Response\Job;

use Detail\FileConversion\Processing\Adapter;
use Detail\FileConversion\Processing\Task;
use Detail\FileConversion\Processing\Exception;

class InternalAdapter extends Adapter\BaseAdapter
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
        FileConversionClient $client,
        InternalJobCreatorInterface $jobCreator,
        array $options = array()
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

        } catch (ClientException\BadRequestException $e) {
            // The request couldn't be sent (e.g. network problems, performance issues, etc.)
            // Note that the request could have timed out...
            // It's possible, the processing can be started successfully upon retry.
            throw new Exception\ProcessingUnavailableException(
                sprintf(
                    'Failed to start processing because DWS FileConversion seems to be unavailable: %s',
                    $e->getMessage()
                ),
                0,
                $e
            );
        } catch (ClientException\BadResponseException $e) {
            // 4xx and 5xx problems (we don't know if the problems is only with this job or
            // if DWS FileConversion's having server side problems - in case of 5xx errors).
            // Either way, we need to fail...
            throw new Exception\ProcessingFailedException(
                sprintf('Processing failed immediately after submitting the job: %s', $e->getMessage()),
                0,
                $e
            );
        } catch (\Exception $e) {
            // Also fail when something else went wrong...
            throw new Exception\ProcessingFailedException(
                sprintf('Failed to start processing: %s', $e->getMessage()),
                1,
                $e
            );
        }
    }

    /**
     * @param Task\TaskInterface $task
     * @return Task\Result|null
     */
    public function checkProcessing(Task\TaskInterface $task)
    {
        $client = $this->getClient();

        try {
            $job = $client->fetchJob(array('job_id' => $task->getProcessId()));

        } catch (ClientException\BadRequestException $e) {
            // The request couldn't be sent (e.g. network problems, performance issues, etc.)
            // Note that the request could have timed out...
            // It's possible, the processing can be checked upon retry.
            throw new Exception\ProcessingUnavailableException(
                sprintf(
                    'Failed to check processing because DWS FileConversion seems to be unavailable: %s',
                    $e->getMessage()
                ),
                0,
                $e
            );
        } catch (ClientException\BadResponseException $e) {
            // 4xx and 5xx problems (we don't know if the problems is only with this job or
            // if DWS FileConversion's having server side problems - in case of 5xx errors).
            // Either way, we need to fail...
            throw new Exception\ProcessingFailedException(
                sprintf('Processing failed after checking the job: %s', $e->getMessage()),
                0,
                $e
            );
        } catch (\Exception $e) {
            // Also fail when something else went wrong...
            throw new Exception\ProcessingFailedException(
                sprintf('Failed to check processing: %s', $e->getMessage()),
                1,
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
     * @return Task\Result
     */
    public function endProcessing(Task\TaskInterface $task, $job)
    {
        $job = $this->getJobResponse($job);

        // Consider everything but error states as success
        if (!in_array($job->getStatus(), array('error', 'error_notifying'))) {
            $outputs = array();
            $failedOutputs = array(); /** @todo Build from action status */

            foreach ($job->getResults() as $result) {
                $outputs[] = new Task\Output(
                    $result->getIdentifier(),
                    $result->getUrl(),
                    $result->getMeta()
                );
            }

            $result = new Task\Result($task, $outputs, $failedOutputs, $job->getSourceMeta());
        } else {
            /** @todo Provide specific error message */
            throw new Exception\ProcessingFailedException(
                'Processing failed because of an error during the conversion'
            );
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

        return $jobCreator->create($task, $this->getClient()->getJobBuilder());
    }

    /**
     * @param Job|array $job
     * @return Job
     */
    protected function getJobResponse($job)
    {
        if (is_array($job)) {
            $job = new Job($job);
        } elseif (!$job instanceof Job) {
            throw new Exception\RuntimeException(
                'Invalid response; expected array or Detail\FileConversion\Client\Response\Job object'
            );
        }

        return $job;
    }
}

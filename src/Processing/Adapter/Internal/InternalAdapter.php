<?php

namespace Detail\FileConversion\Processing\Adapter\Internal;

use GuzzleHttp\Exception as GuzzleException;

use Detail\FileConversion\Client\FileConversionClient;
use Detail\FileConversion\Client\Response\Job;

use Detail\FileConversion\Processing\Action;
use Detail\FileConversion\Processing\Adapter;
use Detail\FileConversion\Processing\Task;
use Detail\FileConversion\Processing\Exception;

class InternalAdapter extends Adapter\BaseAdapter
{
    /**
     * @var string[]
     */
    protected static $supportedActions = [
        Action\ThumbnailAction::NAME => Action\ThumbnailAction::CLASS,
    ];

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
        array $options = []
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
        } catch (GuzzleException\ClientException $e) {
            // Invalid job or 4xx problems.
            // Retrying won't change anything, so we need to fail...
            throw new Exception\ProcessingFailedException(
                sprintf('Processing failed immediately after submitting the job: %s', $e->getMessage()),
                0,
                $e
            );
        } catch (GuzzleException\RequestException $e) {
            // The request couldn't be processed (e.g. network problems, performance issues, 5xx problems, etc.)
            // It's possible, the processing can be started successfully upon retry.
            throw new Exception\ProcessingUnavailableException(
                sprintf(
                    'Failed to start processing because DWS FileConversion seems to be unavailable: %s',
                    $e->getMessage()
                ),
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
            $job = $client->fetchJob(['job_id' => $task->getProcessId()]);
        } catch (GuzzleException\ClientException $e) {
            // Invalid job or 4xx problems.
            // Retrying won't change anything, so we need to fail...
            throw new Exception\ProcessingFailedException(
                sprintf('Processing failed after checking the job: %s', $e->getMessage()),
                0,
                $e
            );
        } catch (GuzzleException\RequestException $e) {
            // The request couldn't be processed (e.g. network problems, performance issues, 5xx problems, etc.)
            // It's possible, the processing can be started successfully upon retry.
            throw new Exception\ProcessingUnavailableException(
                sprintf(
                    'Failed to check processing because DWS FileConversion seems to be unavailable: %s',
                    $e->getMessage()
                ),
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
        if (in_array($job->getStatus(), ['complete', 'error'])) {
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
        if (!in_array($job->getStatus(), ['error', 'error_notifying'])) {
            $outputs = [];
            $failedOutputs = []; /** @todo Build from action status */

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
            $job = Job::fromResult($job);
        } elseif (!$job instanceof Job) {
            throw new Exception\RuntimeException(
                'Invalid response; expected array or Detail\FileConversion\Client\Response\Job object'
            );
        }

        return $job;
    }

    /**
     * @param Task\TaskInterface $task
     * @return string[]
     */
    protected function getTaskActions(Task\TaskInterface $task)
    {
        return $this->getJobCreator()->getActions($task);
    }

    /**
     * @param Task\TaskInterface $task
     * @return array
     */
    protected function getTaskActionParams(Task\TaskInterface $task)
    {
        return $this->getJobCreator()->getActionParams($task);
    }
}

<?php

namespace Detail\FileConversion\Processing\Adapter\Blitline;

use Detail\Blitline\Client\BlitlineClient;
use Detail\Blitline\Exception as BlitlineException;
use Detail\Blitline\Response\JobProcessed as BlitlineJobProcessedResponse;

use Detail\FileConversion\Processing\Action;
use Detail\FileConversion\Processing\Adapter;
use Detail\FileConversion\Processing\Exception;
use Detail\FileConversion\Processing\Task;

class BlitlineAdapter extends Adapter\BaseAdapter
{
    /**
     * @var string[]
     */
    protected static $supportedActions = array(
        Action\ThumbnailAction::NAME => Action\ThumbnailAction::CLASS,
    );

    /**
     * @var BlitlineClient
     */
    protected $client;

    /**
     * @var BlitlineJobCreatorInterface
     */
    protected $jobCreator;

    /**
     * @param BlitlineClient $client
     * @param BlitlineJobCreatorInterface $jobCreator
     * @param array $options
     */
    public function __construct(
        BlitlineClient $client,
        BlitlineJobCreatorInterface $jobCreator,
        array $options = array()
    ) {
        parent::__construct($options);

        $this->setClient($client);
        $this->setJobCreator($jobCreator);
    }

    /**
     * @return BlitlineClient
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param BlitlineClient $client
     */
    public function setClient(BlitlineClient $client)
    {
        $this->client = $client;
    }

    /**
     * @return BlitlineJobCreatorInterface
     */
    public function getJobCreator()
    {
        return $this->jobCreator;
    }

    /**
     * @param BlitlineJobCreatorInterface $jobCreator
     */
    public function setJobCreator(BlitlineJobCreatorInterface $jobCreator)
    {
        $this->jobCreator = $jobCreator;
    }

    /**
     * @param Task\TaskInterface $task
     * @return string Process identifier
     */
    public function startProcessing(Task\TaskInterface $task)
    {
        $job = $this->createBlitlineJob($task);
        $client = $this->getClient();

        try {
            $response = $client->submitJob($job);
            return $response->getJobId();
        } catch (BlitlineException\ServerException $e) {
            // The request couldn't be processed (e.g. network problems, performance issues, 5xx problems, etc.)
            // It's possible, the processing can be started successfully upon retry.
            throw new Exception\ProcessingUnavailableException(
                sprintf(
                    'Failed to start processing because Blitline seems to be unavailable: %s',
                    $e->getMessage()
                ),
                0,
                $e
            );
        } catch (BlitlineException\ClientException $e) {
            // Invalid job or 4xx problems.
            // Retrying won't change anything, so we need to fail...
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
            $response = $client->pollJob(array('job_id' => $task->getProcessId()));
        } catch (BlitlineException\ServerException $e) {
            // The request couldn't be processed (e.g. network problems, performance issues, 5xx problems, etc.)
            // It's possible, the processing can be started successfully upon retry.
            throw new Exception\ProcessingUnavailableException(
                sprintf(
                    'Failed to check processing because Blitline seems to be unavailable: %s',
                    $e->getMessage()
                ),
                0,
                $e
            );
        } catch (BlitlineException\ClientException $e) {
            // Invalid job or 4xx problems.
            // Retrying won't change anything, so we need to fail...
            throw new Exception\ProcessingFailedException(
                sprintf('Processing failed after polling for the completion of the job: %s', $e->getMessage()),
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

        return $this->endProcessing($task, $response);
    }

    /**
     * @param Task\TaskInterface $task
     * @param BlitlineJobProcessedResponse|array $response
     * @return Task\Result
     */
    public function endProcessing(Task\TaskInterface $task, $response)
    {
        $response = $this->getJobProcessedResponse($response);
        $outputs = array();

        foreach ($response->getImages() as $image) {
            $outputs[] = new Task\Output(
                isset($image['image_identifier']) ? $image['image_identifier'] : null,
                isset($image['s3_url']) ? $image['s3_url'] : null,
                isset($image['meta']) && is_array($image['meta']) ? $image['meta'] : array()
            );
        }

        $failedOutputs = array();

        foreach ($response->getFailedImageIdentifiers() as $identifier) {
            $failedOutputs[] = new Task\Output($identifier);
        }

        return new Task\Result(
            $task,
            $outputs,
            $failedOutputs,
            $response->getOriginalMeta(),
            $response->getErrors()
        );
    }

//
//    /**
//     * @param string $type
//     * @return boolean
//     */
//    public function supportsSavingType($type)
//    {
//        /** @todo Replace with real implementation */
//        return true;
//    }

    /**
     * @param Task\TaskInterface $task
     * @return \Detail\Blitline\Job\Definition\JobDefinitionInterface
     */
    protected function createBlitlineJob(Task\TaskInterface $task)
    {
        $jobCreator = $this->getJobCreator();

        if ($jobCreator === null) {
            throw new Exception\RuntimeException(
                'Blitline job creator is required to create Blitline jobs'
            );
        }

        return $jobCreator->create($task, $this->getClient()->getJobBuilder());
    }

    /**
     * @param BlitlineJobProcessedResponse|array $response
     * @return BlitlineJobProcessedResponse
     */
    protected function getJobProcessedResponse($response)
    {
        if (!$response instanceof BlitlineJobProcessedResponse) {
            if (!is_array($response)) {
                throw new Exception\ProcessingFailedException(
                    'Invalid response; expected array or Detail\Blitline\Response\JobProcessed object'
                );
            }

            try {
                $response = BlitlineJobProcessedResponse::fromData($response);
            } catch (\Exception $e) {
                throw new Exception\ProcessingFailedException(
                    sprintf(
                        'Processing failed after response data have been received: %s',
                        $e->getMessage()
                    ),
                    0,
                    $e
                );
            }
        }

        return $response;
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

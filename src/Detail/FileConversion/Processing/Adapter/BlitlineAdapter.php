<?php

namespace Detail\FileConversion\Processing\Adapter;

use Detail\Blitline\Client\BlitlineClient;
use Detail\Blitline\Client\Exception as BlitlineClientException;
use Detail\Blitline\Response\JobProcessed as BlitlineJobProcessedResponse;

use Detail\FileConversion\Processing\Task;
use Detail\FileConversion\Processing\Exception;

class BlitlineAdapter extends BaseAdapter //implements
//    Features\Polling,
//    Features\SynchronousProcessing,
//    Features\AsynchronousProcessing,
//    Features\Saving
{
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

        } catch (BlitlineClientException\BadRequestException $e) {
            // The request couldn't be sent (e.g. network problems, performance issues, etc.)
            // Note that the request could have timed out...
            // It's possible, the processing can be started successfully upon retry.
            throw new Exception\ProcessingUnavailableException(
                sprintf(
                    'Failed to start processing because Blitline seems to be unavailable: %s',
                    $e->getMessage()
                ),
                0,
                $e
            );
        } catch (BlitlineClientException\BadResponseException $e) {
            // 4xx and 5xx problems (we don't know if the problems is only with this job or
            // if Blitline's having server side problems (in case of 5xx errors).
            // Either way, we need to fail...
            throw new Exception\ProcessingFailedException(
                sprintf('Processing failed immediately after submitting the job: %s',$e->getMessage()),
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

        } catch (BlitlineClientException\BadRequestException $e) {
            // The request couldn't be sent (e.g. network problems, performance issues, etc.)
            // Note that the request could have timed out...
            // It's possible, the processing can be checked upon retry.
            throw new Exception\ProcessingUnavailableException(
                sprintf(
                    'Failed to check processing because Blitline seems to be unavailable: %s',
                    $e->getMessage()
                ),
                0,
                $e
            );
        } catch (BlitlineClientException\BadResponseException $e) {
            // 4xx and 5xx problems (we don't know if the problems is only with this job or
            // if Blitline's having server side problems (in case of 5xx errors).
            // Either way, we need to fail...
            throw new Exception\ProcessingFailedException(
                sprintf('Processing failed after polling for the completion of the job: %s',$e->getMessage()),
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
        if (!$response instanceof BlitlineJobProcessedResponse) {
            if (!is_array($response)) {
                throw new Exception\ProcessingFailedException(
                    'Invalid response; expected array or Detail\Blitline\Response\JobProcessed object'
                );
            }

            try {
                $response = BlitlineJobProcessedResponse::fromResponse($response);
            } catch (\Exception $e) {
                throw new Exception\ProcessingFailedException(
                    sprintf('Processing failed after response data have been received: %s', $e->getMessage()),
                    0,
                    $e
                );
            }
        }

        $outputs = array();

        foreach ($response->getImages() as $image) {
            $outputs[] = new Task\Output(
                isset($image['image_identifier']) ? $image['image_identifier'] : null,
                isset($image['s3_url']) ? $image['s3_url'] : null,
                isset($image['meta']) && is_array($image['meta']) ? $image['meta'] : array()
            );
        }

        if (count($outputs) === 0) {
            throw new Exception\ProcessingFailedException(
                'Processing failed because there were no images in the response'
            );
        }

        return new Task\Result($task, $outputs, $response->getOriginalMeta());
    }

//    /**
//     * @param string $actionName
//     * @return bool
//     */
//    public function supportsAction($actionName)
//    {
//        /** @todo Replace with real implementation */
//        return true;
//    }
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
}

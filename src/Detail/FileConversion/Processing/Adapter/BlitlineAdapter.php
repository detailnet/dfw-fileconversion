<?php

namespace Detail\FileConversion\Processing\Adapter;

use Detail\Blitline\Client\BlitlineClient;
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

        } catch (\Exception $e) {
            throw new Exception\RuntimeException(
                sprintf('Blitline API request failed: %s', $e->getMessage()),
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
            $response = $client->pollJob(array('job_id' => $task->getProcessId()));

        } catch (\Exception $e) {
            throw new Exception\RuntimeException(
                sprintf('Blitline API request failed: %s', $e->getMessage()),
                0,
                $e
            );
        }

        return $this->endProcessing($task, $response);
    }

    /**
     * @param Task\TaskInterface $task
     * @param BlitlineJobProcessedResponse|array $response
     * @return Task\ResultInterface
     */
    public function endProcessing(Task\TaskInterface $task, $response)
    {
        $response = $this->getJobProcessedResponse($response);

        if ($response->isSuccess()) {
            $outputs = array();

            foreach ($response->getImages() as $image) {
                $outputs[] = new Task\Output(
                    isset($image['image_identifier']) ? $image['image_identifier'] : null,
                    isset($image['s3_url']) ? $image['s3_url'] : null,
                    isset($image['meta']) && is_array($image['meta']) ? $image['meta'] : array()
                );
            }

            /** @todo We should probably fail when there are no outputs... */

            $result = new Task\SuccessResult($task, $outputs, $response->getOriginalMeta());
        } else {
            $result = new Task\ErrorResult($task, $response->getError());
        }

        return $result;
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

    /**
     * @param array $response
     * @return BlitlineJobProcessedResponse
     */
    protected function getJobProcessedResponse(array $response)
    {
        if (is_array($response)) {
            if (!isset($response['results']) || !is_array($response['results'])) {
                throw new Exception\RuntimeException('Unexpected response format; contains no result');
            }

            $response = new BlitlineJobProcessedResponse($response['results']);

        } elseif (!$response instanceof BlitlineJobProcessedResponse) {
            throw new Exception\RuntimeException(
                'Invalid response; expected array or Detail\Blitline\Response\JobProcessed object'
            );
        }

        return $response;
    }
}

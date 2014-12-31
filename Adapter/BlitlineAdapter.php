<?php

namespace Application\Job\Application\JobProcessing\Adapter;

use Detail\Blitline\Client\BlitlineClient;

use Application\Job\Application\JobProcessing\Task;
use Application\Job\Domain\Exception\RuntimeException;


class BlitlineAdapter extends BaseAdapter //implements
//    Features\Polling,
//    Features\SynchronousProcessing,
//    Features\AsynchronousProcessing,
//    Features\Saving
{
    /**
     * @var BlitlineClient
     */
    protected $blitlineClient;

    /**
     * @param BlitlineClient $blitlineClient
     */
    public function __construct(BlitlineClient $blitlineClient)
    {
        $this->blitlineClient = $blitlineClient;
    }

    /**
     * @return BlitlineClient
     */
    public function getBlitlineClient()
    {
        return $this->blitlineClient;
    }

    /**
     * @param BlitlineClient $blitlineClient
     */
    public function setBlitlineClient($blitlineClient)
    {
        $this->blitlineClient = $blitlineClient;
    }

    /**
     * @param Task\TaskInterface $task
     * @return string Process identifier
     */
    public function startProcessing(Task\TaskInterface $task)
    {
        $job = $this->createBlitlineJob($task);

        $client = $this->getBlitlineClient();

        try {
            $response = $client->postJob($job);
            $processId = $this->handleBlitlineResponse($response);

        } catch (\Exception $e) {
            throw new RuntimeException(
                sprintf('Blitline API request failed: %s', $e->getMessage()),
                0,
                $e
            );
        }

        return $processId;
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
        $jobBuilder = $this->getBlitlineClient()->getJobBuilder();
        $job = $task->getJob();

        $blitlineJob = $jobBuilder->createJob()
            ->setSourceUrl($job->getSourceUrl());

        foreach ($job->getActions() as $action) {
            $saveOptions = $action->getSaveOptions();
            $saveOptionsData = array(
                'image_identifier' => $saveOptions->getIdentifier(),
            );

            switch ($saveOptions->getType()) {
                case $saveOptions::TYPE_S3:
                    $saveOptionsData['s3_destination'] = $saveOptions->getParams();
                    break;
                default:
                    throw new RuntimeException(
                        sprintf(
                            'Adapter does not support save options type "%s"',
                            $saveOptions->getType()
                        )
                    );
                    break;
            }

            $blitlineJob->addFunction(
                $jobBuilder->createFunction()
                    ->setName($action->getName())
                    ->setParams($action->getParams())
                    ->setSaveOptions($saveOptionsData)
            );
        }

        return $blitlineJob;
    }

    /**
     * @param array $response
     * @return string Blitline job identifier
     */
    protected function handleBlitlineResponse(array $response)
    {
        if (!isset($response['results']) || !is_array($response['results'])) {
            throw new RuntimeException('Unexpected response format; contains no result');
        }

        $result = $response['results'];

        if (isset($result['error'])) {
            throw new RuntimeException(sprintf('Received error; %s', $result['error']));
        }

        if (!isset($result['job_id']) || !is_string($result['job_id'])) {
            throw new RuntimeException('Unexpected response format; contains no job identifier');
        }

        return $result['job_id'];
    }
}

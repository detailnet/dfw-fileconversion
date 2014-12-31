<?php

namespace Application\Job\Application\JobProcessing\Adapter;

use Application\Job\Application\JobProcessing\Task;
use Detail\Blitline\Client\BlitlineClient;

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
        $response = $client->postJob($job);

        var_dump($response);
        exit;
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

        /** @todo Actually build the job */

        $job = $jobBuilder->createJob()
            ->setSourceUrl('');

        return $job;
    }
}

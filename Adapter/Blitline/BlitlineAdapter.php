<?php

namespace Application\Job\Application\JobProcessing\Adapter\Blitline;

use Detail\Blitline\Client\BlitlineClient;

use Application\Job\Application\JobProcessing\Adapter\BaseAdapter;
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
            $response = $this->getBlitlineResponse($client->postJob($job));
            return $response->getJobId();

        } catch (\Exception $e) {
            throw new RuntimeException(
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
        $client = $this->getBlitlineClient();

        try {
            $data = $client->pollJob(array('jobId' => $task->getProcessId()));

        } catch (\Exception $e) {
            throw new RuntimeException(
                sprintf('Blitline API request failed: %s', $e->getMessage()),
                0,
                $e
            );
        }

        return $this->endProcessing($task, $data);
    }

    /**
     * @param Task\TaskInterface $task
     * @param array $data
     * @return Task\ResultInterface
     */
    public function endProcessing(Task\TaskInterface $task, array $data)
    {
        $response = $this->getBlitlineResponse($data);

        if ($response->isSuccess()) {
            $outputs = array();

            foreach ($response->getImages() as $image) {
                $outputs[] = new Task\Output(
                    isset($image['image_identifier']) ? $image['image_identifier'] : null,
                    isset($image['s3_url']) ? $image['s3_url'] : null,
                    isset($image['meta']) && is_array($image['meta']) ? $image['meta'] : null
                );
            }

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

    protected function getBlitlineResponse(array $response)
    {
        if (!isset($response['results']) || !is_array($response['results'])) {
            throw new RuntimeException('Unexpected response format; contains no result');
        }

        return BlitlineResponse::fromArray($response['results']);
    }
}

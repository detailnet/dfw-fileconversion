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
    const OPTION_POSTBACK_URL = 'postback_url';

    /**
     * @var BlitlineClient
     */
    protected $blitlineClient;

    /**
     * @var BlitlineJobCreatorInterface
     */
    protected $blitlineJobCreator;

    /**
     * @param BlitlineClient $blitlineClient
     * @param BlitlineJobCreatorInterface $blitlineJobCreator
     * @param array $options
     */
    public function __construct(
        BlitlineClient $blitlineClient,
        BlitlineJobCreatorInterface $blitlineJobCreator,
        array $options = array()
    ) {
        parent::__construct($options);

        $this->setBlitlineClient($blitlineClient);
        $this->setBlitlineJobCreator($blitlineJobCreator);
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
    public function setBlitlineClient(BlitlineClient $blitlineClient)
    {
        $this->blitlineClient = $blitlineClient;
    }

    /**
     * @return BlitlineJobCreatorInterface
     */
    public function getBlitlineJobCreator()
    {
        return $this->blitlineJobCreator;
    }

    /**
     * @param BlitlineJobCreatorInterface $blitlineJobCreator
     */
    public function setBlitlineJobCreator(BlitlineJobCreatorInterface $blitlineJobCreator)
    {
        $this->blitlineJobCreator = $blitlineJobCreator;
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
        $client = $this->getBlitlineClient();

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
        if (is_array($response)) {
            $response = $this->getBlitlineJobProcessedResponse($response);
        } else if (!$response instanceof BlitlineJobProcessedResponse) {
            throw new Exception\RuntimeException(
                'Invalid response; expected array or Detail\Blitline\Response\JobProcessed object'
            );
        }

        if ($response->isSuccess()) {
            $outputs = array();

            foreach ($response->getImages() as $image) {
                $outputs[] = new Task\Output(
                    isset($image['image_identifier']) ? $image['image_identifier'] : null,
                    isset($image['s3_url']) ? $image['s3_url'] : null,
                    isset($image['meta']) && is_array($image['meta']) ? $image['meta'] : array()
                );
            }

            /** @todo We should probably fail when there are not outputs... */

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
        $jobCreator = $this->getBlitlineJobCreator();

        if ($jobCreator === null) {
            throw new Exception\RuntimeException(
                'Blitline job creator is required to create Blitline jobs'
            );
        }

        return $jobCreator->create($task);

//        $jobBuilder = $this->getBlitlineClient()->getJobBuilder();
//        $job = $task->getJob();
//
//        $blitlineJob = $jobBuilder->createJob()
//            ->setSourceUrl($job->getSourceUrl());
//
//        $postbackUrl = $this->getOption(self::OPTION_POSTBACK_URL);
//
//        if ($postbackUrl !== null) {
//            $blitlineJob->setPostbackUrl($postbackUrl);
//        }
//
//        foreach ($job->getActions() as $action) {
//            $saveOptions = $action->getSaveOptions();
//            $saveOptionsData = array(
//                'image_identifier' => $saveOptions->getIdentifier(),
//            );
//
//            switch ($saveOptions->getType()) {
//                case $saveOptions::TYPE_S3:
//                    $saveOptionsData['s3_destination'] = $saveOptions->getParams();
//                    break;
//                default:
//                    throw new Exception\RuntimeException(
//                        sprintf(
//                            'Adapter does not support save options type "%s"',
//                            $saveOptions->getType()
//                        )
//                    );
//                    break;
//            }
//
//            $blitlineJob->addFunction(
//                $jobBuilder->createFunction()
//                    ->setName($action->getName())
//                    ->setParams($action->getParams())
//                    ->setSaveOptions($saveOptionsData)
//            );
//        }
//
//        return $blitlineJob;
    }

    /**
     * @param array $response
     * @return BlitlineJobProcessedResponse
     */
    protected function getBlitlineJobProcessedResponse(array $response)
    {
        if (!isset($response['results']) || !is_array($response['results'])) {
            throw new Exception\RuntimeException('Unexpected response format; contains no result');
        }

        return new BlitlineJobProcessedResponse($response['results']);
    }
}

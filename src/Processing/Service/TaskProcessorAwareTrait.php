<?php

namespace Detail\FileConversion\Processing\Service;

use Detail\FileConversion\Processing\TaskProcessorInterface;

trait TaskProcessorAwareTrait
{
    /**
     * @var TaskProcessorInterface
     */
    protected $taskProcessor;

    /**
     * @return TaskProcessorInterface
     */
    public function getTaskProcessor()
    {
        return $this->taskProcessor;
    }

    /**
     * @param TaskProcessorInterface $taskProcessor
     */
    public function setTaskProcessor(TaskProcessorInterface $taskProcessor)
    {
        $this->taskProcessor = $taskProcessor;
    }
}

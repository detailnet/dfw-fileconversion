<?php

namespace Detail\FileConversion\Processing;

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

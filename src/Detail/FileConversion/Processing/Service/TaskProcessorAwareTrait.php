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
    public function getJobProcessor()
    {
        return $this->taskProcessor;
    }

    /**
     * @param TaskProcessorInterface $taskProcessor
     */
    public function setJobProcessor(TaskProcessorInterface $taskProcessor)
    {
        $this->taskProcessor = $taskProcessor;
    }
}

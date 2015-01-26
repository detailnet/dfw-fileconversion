<?php

namespace Detail\FileConversion\Processing\Service;

use Detail\FileConversion\Processing\TaskProcessorInterface;

interface TaskProcessorAwareInterface
{
    /**
     * @param TaskProcessorInterface $taskProcessor
     */
    public function setTaskProcessor(TaskProcessorInterface $taskProcessor);
}

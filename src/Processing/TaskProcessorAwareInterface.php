<?php

namespace Detail\FileConversion\Processing;

interface TaskProcessorAwareInterface
{
    /**
     * @param TaskProcessorInterface $taskProcessor
     */
    public function setTaskProcessor(TaskProcessorInterface $taskProcessor);
}

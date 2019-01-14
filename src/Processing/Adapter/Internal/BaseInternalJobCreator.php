<?php

namespace Detail\FileConversion\Processing\Adapter\Internal;

use Detail\FileConversion\Processing\Adapter;
use Detail\FileConversion\Processing\Task;

abstract class BaseInternalJobCreator extends Adapter\BaseJobCreator implements
    InternalJobCreatorInterface
{
    /**
     * Extract task's actions.
     *
     * @param Task\TaskInterface $task
     * @return string[]
     */
    public function getActions(Task\TaskInterface $task)
    {
        return array_keys($this->getActionParams($task));
    }
}

<?php

namespace Detail\FileConversion\Processing\Task;

class ErrorResult extends BaseResult
{
    /**
     * @param TaskInterface $task
     * @param string $error
     */
    public function __construct(TaskInterface $task, $error)
    {
        parent::__construct($task, array(), array(), $error);
    }
}

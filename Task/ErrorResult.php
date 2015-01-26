<?php

namespace Application\Job\Application\JobProcessing\Task;

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

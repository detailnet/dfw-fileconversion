<?php

namespace Application\Job\Application\JobProcessing\Task;

class SuccessResult extends BaseResult
{
    /**
     * @param TaskInterface $task
     * @param array $outputs
     * @param array $originalMeta
     */
    public function __construct(TaskInterface $task, array $outputs, array $originalMeta = array())
    {
        parent::__construct($task, $outputs, $originalMeta);
    }
}

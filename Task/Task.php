<?php

namespace Application\Job\Application\JobProcessing\Task;

use Application\Job\Domain\Model\Job;

class Task implements
    TaskInterface
{
    /**
     * @var string
     */
    protected $processId;

    public static function fromJob(Job $job)
    {
        $task = new static();

        /** @todo Actually populate the task */

        return $task;
    }

    /**
     * @return string
     */
    public function getProcessId()
    {
        return $this->processId;
    }

    /**
     * @param string $processId
     */
    public function setProcessId($processId)
    {
        $this->processId = $processId;
    }
}

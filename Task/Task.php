<?php

namespace Application\Job\Application\JobProcessing\Task;

use Application\Job\Domain\Model\Job;

class Task implements
    TaskInterface
{
    const PRIORITY_LOW    = -1;
    const PRIORITY_NORMAL = 0;
    const PRIORITY_HIGH   = 1;

    /**
     * @var Job
     */
    protected $job;

    /**
     * @var string
     */
    protected $processId;

    /**
     * @var int
     */
    protected $priority = self::PRIORITY_NORMAL;

    /**
     * @param Job $job
     * @param int $priority
     * @return Task
     */
    public static function fromJob(Job $job, $priority = null)
    {
        $task = new static($job, $priority);
        $task->setProcessId($job->getProcessId());

        return $task;
    }

    /**
     * @param Job $job
     * @param int $priority
     */
    public function __construct(Job $job, $priority = null)
    {
        $this->job = $job;

        if ($priority !== null) {
            $this->priority = $priority;
        }
    }

    /**
     * @return Job
     */
    public function getJob()
    {
        return $this->job;
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

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }
}

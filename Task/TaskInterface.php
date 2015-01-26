<?php

namespace Application\Job\Application\JobProcessing\Task;

use Application\Job\Domain\Model\Job;

interface TaskInterface
{
    /**
     * @return Job
     */
    public function getJob();

    /**
     * @return string
     */
    public function getProcessId();

    /**
     * @param string $processId
     */
    public function setProcessId($processId);

    /**
     * @return int
     */
    public function getPriority();

    /**
     * @param int $priority
     */
    public function setPriority($priority);
}

<?php

namespace Detail\FileConversion\Processing\Task;

class Task implements
    TaskInterface
{
    const PRIORITY_LOW    = -1;
    const PRIORITY_NORMAL = 0;
    const PRIORITY_HIGH   = 1;

    /**
     * @var string
     */
    protected $processId;

    /**
     * @var int
     */
    protected $priority = self::PRIORITY_NORMAL;

    /**
     * @param int $priority
     */
    public function __construct($priority = null)
    {
        if ($priority !== null) {
            $this->priority = $priority;
        }
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

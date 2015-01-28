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
     * @var string
     */
    protected $adapter;

    /**
     * @param int $priority
     * @param string $adapter
     */
    public function __construct($priority = null, $adapter = null)
    {
        if ($priority !== null) {
            $this->setPriority($priority);
        }

        if ($adapter !== null) {
            $this->setAdapter($adapter);
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

    /**
     * @return string
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @param string $adapter
     */
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
    }
}

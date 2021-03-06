<?php

namespace Detail\FileConversion\Processing\Task;

interface TaskInterface
{
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

    /**
     * @return string
     */
    public function getAdapter();

    /**
     * @param string $adapter
     */
    public function setAdapter($adapter);
}

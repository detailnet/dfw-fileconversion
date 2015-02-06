<?php

namespace Detail\FileConversion\Processing\Task;

class Result implements
    ResultInterface
{
    /**
     * @var TaskInterface
     */
    protected $task;

    /**
     * @var array
     */
    protected $outputs = array();

    /**
     * @var array
     */
    protected $originalMeta = array();

    /**
     * @param TaskInterface $task
     * @param array $outputs
     * @param array $originalMeta
     */
    public function __construct(TaskInterface $task, array $outputs, array $originalMeta = array())
    {
        $this->task = $task;
        $this->outputs = $outputs;
        $this->originalMeta = $originalMeta;
    }

    /**
     * @return TaskInterface
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * @return array
     */
    public function getOutputs()
    {
        return $this->outputs;
    }

    /**
     * @return array
     */
    public function getOriginalMeta()
    {
        return $this->originalMeta;
    }
}

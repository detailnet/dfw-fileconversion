<?php

namespace Detail\FileConversion\Processing\Task;

abstract class BaseResult implements
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
     * @var string
     */
    protected $error;

    /**
     * @param TaskInterface $task
     * @param array $outputs
     * @param array $originalMeta
     * @param string $error
     */
    public function __construct(TaskInterface $task, array $outputs, array $originalMeta = array(), $error = null)
    {
        $this->task = $task;
        $this->outputs = $outputs;
        $this->originalMeta = $originalMeta;

        if ($error !== null) {
            $this->error = $error;
        }
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

    /**
     * @return boolean
     */
    public function isSuccess()
    {
        return !$this->isError();
    }

    /**
     * @return boolean
     */
    public function isError()
    {
        return $this->getError() !== null;
    }

    /**
     * @return string|null
     */
    public function getError()
    {
        return $this->error;
    }
}

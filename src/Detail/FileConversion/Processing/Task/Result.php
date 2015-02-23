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
    protected $outputs;

    /**
     * @var array
     */
    protected $failedOutputs;

    /**
     * @var array
     */
    protected $originalMeta;

    /**
     * @var array
     */
    protected $errors;

    /**
     * @param TaskInterface $task
     * @param array $outputs
     * @param array $failedOutputs
     * @param array $originalMeta
     * @param array $errors
     */
    public function __construct(
        TaskInterface $task,
        array $outputs,
        array $failedOutputs = array(),
        array $originalMeta = array(),
        array $errors = array()
    ) {
        $this->task = $task;
        $this->outputs = $outputs;
        $this->failedOutputs = $failedOutputs;
        $this->originalMeta = $originalMeta;
        $this->errors = $errors;
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
     * @return boolean
     */
    public function hasOutputs()
    {
        return count($this->getOutputs()) > 0;
    }

    /**
     * @return array
     */
    public function getFailedOutputs()
    {
        return $this->failedOutputs;
    }

    /**
     * @return boolean
     */
    public function hasFailedOutputs()
    {
        return count($this->getFailedOutputs()) > 0;
    }

    /**
     * @return array
     */
    public function getOriginalMeta()
    {
        return $this->originalMeta;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return boolean
     */
    public function hasErrors()
    {
        return count($this->getErrors()) > 0;
    }
}

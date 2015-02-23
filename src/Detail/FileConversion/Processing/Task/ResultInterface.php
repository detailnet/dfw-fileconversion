<?php

namespace Detail\FileConversion\Processing\Task;

interface ResultInterface
{
    /**
     * @return TaskInterface
     */
    public function getTask();

    /**
     * @return array
     */
    public function getOutputs();

    /**
     * @return boolean
     */
    public function hasOutputs();

    /**
     * @return array
     */
    public function getFailedOutputs();

    /**
     * @return boolean
     */
    public function hasFailedOutputs();

    /**
     * @return array
     */
    public function getOriginalMeta();

    /**
     * @return array
     */
    public function getErrors();

    /**
     * @return boolean
     */
    public function hasErrors();
}

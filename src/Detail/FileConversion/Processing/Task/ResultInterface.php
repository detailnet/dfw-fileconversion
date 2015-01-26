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
     * @return array
     */
    public function getOriginalMeta();

    /**
     * @return boolean
     */
    public function isSuccess();

    /**
     * @return boolean
     */
    public function isError();

    /**
     * @return string|null
     */
    public function getError();
}

<?php

namespace Detail\FileConversion\Processing\Adapter;

use Detail\FileConversion\Processing\Support;
use Detail\FileConversion\Processing\Task;

interface AdapterInterface
{
    /**
     * @param string $actionName
     * @return boolean
     */
    public function supportsAction($actionName);

    /**
     * @param array $actions
     * @return boolean
     */
    public function supportsActions(array $actions);

    /**
     * @param Task\TaskInterface $task
     * @return Support\AdapterSupport
     */
    public function supportsTask(Task\TaskInterface $task);

    /**
     * @param Task\TaskInterface $task
     * @return string Process identifier
     */
    public function startProcessing(Task\TaskInterface $task);

    /**
     * @param Task\TaskInterface $task
     * @return Task\ResultInterface|null
     */
    public function checkProcessing(Task\TaskInterface $task);

    /**
     * @param Task\TaskInterface $task
     * @param mixed $response
     * @return Task\ResultInterface
     */
    public function endProcessing(Task\TaskInterface $task, $response);
}

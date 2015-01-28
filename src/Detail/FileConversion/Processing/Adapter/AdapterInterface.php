<?php

namespace Detail\FileConversion\Processing\Adapter;

use Detail\FileConversion\Processing\Task;

interface AdapterInterface
{
//    /**
//     * @param string $actionName
//     * @return bool
//     */
//    public function supportsAction($actionName);

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

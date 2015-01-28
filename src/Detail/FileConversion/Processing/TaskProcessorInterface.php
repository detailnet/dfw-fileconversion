<?php

namespace Detail\FileConversion\Processing;

interface TaskProcessorInterface
{
    /**
     * @param Task\TaskInterface $task
     * @return Task\ResultInterface
     */
    public function process(Task\TaskInterface $task);

    /**
     * @param Task\TaskInterface $task
     * @return string Process identifier
     */
    public function startProcessing(Task\TaskInterface $task);

    /**
     * @param Task\TaskInterface $task
     * @return Task\ResultInterface
     */
    public function checkProcessing(Task\TaskInterface $task);

    /**
     * @param Task\TaskInterface $task
     * @param array $data
     * @return Task\ResultInterface
     */
    public function endProcessing(Task\TaskInterface $task, array $data);
}

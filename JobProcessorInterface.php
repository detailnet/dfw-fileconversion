<?php

namespace Application\Job\Application\JobProcessing;

interface JobProcessorInterface
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
     * @param array $result
     * @return Task\ResultInterface
     */
    public function endProcessing(array $result);
}

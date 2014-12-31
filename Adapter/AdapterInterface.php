<?php

namespace Application\Job\Application\JobProcessing\Adapter;

use Application\Job\Application\JobProcessing\Task;

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
}

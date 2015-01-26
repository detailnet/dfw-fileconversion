<?php

namespace Detail\FileConversion\Processing\Adapter;

use Detail\FileConversion\Processing\Task;

interface InternalJobCreatorInterface
{
    public function create(Task\TaskInterface $task);
}

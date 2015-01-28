<?php

namespace Detail\FileConversion\Processing\Adapter;

use Detail\Blitline\Job\JobBuilderInterface;
use Detail\FileConversion\Processing\Task;

interface BlitlineJobCreatorInterface
{
    public function create(Task\TaskInterface $task, JobBuilderInterface $jobBuilder);
}

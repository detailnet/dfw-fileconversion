<?php

namespace Detail\FileConversion\Processing\Service;

use Detail\FileConversion\Processing\JobProcessorInterface;

trait JobProcessorAwareTrait
{
    /**
     * @var JobProcessorInterface
     */
    protected $jobProcessor;

    /**
     * @return JobProcessorInterface
     */
    public function getJobProcessor()
    {
        return $this->jobProcessor;
    }

    /**
     * @param JobProcessorInterface $jobProcessor
     */
    public function setJobProcessor(JobProcessorInterface $jobProcessor)
    {
        $this->jobProcessor = $jobProcessor;
    }
}

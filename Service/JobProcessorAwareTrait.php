<?php

namespace Application\Job\Application\JobProcessing\Service;

use Application\Job\Application\JobProcessing\JobProcessorInterface;

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

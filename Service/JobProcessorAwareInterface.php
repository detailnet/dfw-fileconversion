<?php

namespace Application\Job\Application\JobProcessing\Service;

use Application\Job\Application\JobProcessing\JobProcessorInterface;

interface JobProcessorAwareInterface
{
    /**
     * @param JobProcessorInterface $jobProcessor
     */
    public function setJobProcessor(JobProcessorInterface $jobProcessor);
}

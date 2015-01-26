<?php

namespace Detail\FileConversion\Processing\Service;

use Detail\FileConversion\Processing\JobProcessorInterface;

interface JobProcessorAwareInterface
{
    /**
     * @param JobProcessorInterface $jobProcessor
     */
    public function setJobProcessor(JobProcessorInterface $jobProcessor);
}

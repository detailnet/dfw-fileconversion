<?php

namespace Detail\FileConversion\Response;

class JobList extends ListResponse
{
    /**
     * @var array
     */
    protected $jobs;

    /**
     * @param boolean $asPlainResult
     * @return array
     */
    public function getItems($asPlainResult = false)
    {
        return $this->getJobs($asPlainResult);
    }

    /**
     * @param boolean $asPlainResult
     * @return array
     */
    protected function getJobs($asPlainResult = false)
    {
        return $this->getSubResults('jobs', array($this, 'createJob'), $asPlainResult);
    }

    /**
     * @param array $data
     * @return Job
     */
    protected function createJob(array $data)
    {
        return new Job($data);
    }
}

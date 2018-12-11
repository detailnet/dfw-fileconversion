<?php

namespace Detail\FileConversion\Client\Response;

class JobList extends ListResponse
{
    /**
     * @var array
     */
    protected $jobs;

    /**
     * @param boolean $asPlainResult
     * @return Job[]|array
     */
    public function getItems($asPlainResult = false)
    {
        return $this->getJobs($asPlainResult);
    }

    /**
     * @param boolean $asPlainResult
     * @return Job[]|array
     */
    protected function getJobs($asPlainResult = false)
    {
        return $this->getSubResults('jobs', [$this, 'createJob'], $asPlainResult);
    }

    /**
     * @param array $data
     * @return Job
     */
    protected function createJob(array $data)
    {
        return Job::fromResult($data);
    }
}

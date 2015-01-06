<?php

namespace  Detail\FileConversion\Response;

class Job extends BaseResponse
{
    /**
     * @var array
     */
    protected $actions;

    /**
     * @var array
     */
    protected $results;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->getResult('id');
    }

    /**
     * @return string
     */
    public function getSourceUrl()
    {
        return $this->getResult('source_url');
    }

    /**
     * @return array
     */
    public function getSourceMeta()
    {
        return $this->getResult('source_meta');
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->getResult('status');
    }

    /**
     * @param boolean $asPlainResult
     * @return array
     */
    public function getActions($asPlainResult = false)
    {
        return $this->getSubResults('actions', array($this, 'createAction'), $asPlainResult);
    }

    /**
     * @param boolean $asPlainResult
     * @return array
     */
    public function getResults($asPlainResult = false)
    {
        return $this->getSubResults('results', array($this, 'createResult'), $asPlainResult);
    }

    /**
     * @param array $data
     * @return Action
     */
    protected function createAction(array $data)
    {
        return new Action($data);
    }

    /**
     * @param array $data
     * @return Result
     */
    protected function createResult(array $data)
    {
        return new Result($data);
    }
}

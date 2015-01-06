<?php

namespace  Detail\FileConversion\Response;

class Job extends BaseResponse
{
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
        return $this->getResult('sourceUrl');
    }

    /**
     * @return array
     */
    public function getSourceMeta()
    {
        return $this->getResult('sourceMeta');
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->getResult('status');
    }

    /**
     * @return array
     */
    public function getActions()
    {
        return $this->getResult('actions');
    }

    /**
     * @return array
     */
    public function getOutputs()
    {
        return $this->getResult('results');
    }
}

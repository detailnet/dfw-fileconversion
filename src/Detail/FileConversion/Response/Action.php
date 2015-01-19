<?php

namespace Detail\FileConversion\Response;

class Action extends BaseResponse
{
    /**
     * @var array
     */
    protected $save;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getResult('name');
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->getResult('params');
    }

    /**
     * @param boolean $asPlainResult
     * @return SaveOptions|array
     */
    public function getSaveOptions($asPlainResult = false)
    {
        return $this->getSubResult('save', array($this, 'createSaveOptions'), $asPlainResult, false);
    }

    /**
     * @param array $data
     * @return SaveOptions
     */
    protected function createSaveOptions(array $data)
    {
        return new SaveOptions($data);
    }
}

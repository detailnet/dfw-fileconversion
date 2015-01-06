<?php

namespace  Detail\FileConversion\Response;

class Action extends BaseResponse
{
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
        return $this->getSubResult('save', array($this, 'createSaveOptions'), $asPlainResult);
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

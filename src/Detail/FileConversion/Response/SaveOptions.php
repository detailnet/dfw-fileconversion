<?php

namespace  Detail\FileConversion\Response;

class SaveOptions extends BaseResponse
{
    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->getResult('identifier');
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->getResult('type');
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->getResult('params');
    }
}

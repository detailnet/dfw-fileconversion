<?php

namespace Detail\FileConversion\Client\Response;

class SaveOptions extends BaseResponse
{
    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->getResult('identifier', false);
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

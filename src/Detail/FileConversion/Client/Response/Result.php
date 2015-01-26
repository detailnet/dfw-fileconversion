<?php

namespace Detail\FileConversion\Client\Response;

class Result extends BaseResponse
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
    public function getIdentifier()
    {
        return $this->getResult('identifier', false);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->getResult('url');
    }

    /**
     * @return array
     */
    public function getMeta()
    {
        return $this->getResult('meta');
    }
}

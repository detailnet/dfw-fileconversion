<?php

namespace Detail\FileConversion\Client\Response;

interface ResponseInterface
{
    /**
     * @param string $key
     * @return array|mixed
     */
    public function getResult($key = null);
}

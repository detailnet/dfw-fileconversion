<?php

namespace Detail\FileConversion\Client\Response;

interface ResponseInterface
{
    /**
     * @param string $expression
     * @return array|mixed
     */
    public function getResult($expression = null);
}

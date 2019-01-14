<?php

namespace Detail\FileConversion\Client\Response;

use GuzzleHttp\Command\Guzzle\Operation;
use GuzzleHttp\Psr7\Response as PsrResponse;

interface ResponseInterface
{
    public static function fromOperation(Operation $operation, PsrResponse $response): ResponseInterface;

    /**
     * @param string $expression
     * @param boolean $failOnNull
     * @return array|mixed
     */
    public function getResult($expression = null, $failOnNull = true);
}

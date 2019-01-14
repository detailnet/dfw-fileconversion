<?php

namespace DetailTest\FileConversion\Client\Response;

use PHPUnit\Framework\TestCase;

use GuzzleHttp\Psr7\Response as PsrResponse;

use Detail\FileConversion\Client\Response\ResponseInterface;

abstract class ResponseTestCase extends TestCase
{
    /**
     * @param array $data
     * @param string $class
     * @param bool $abstract
     * @return ResponseInterface
     */
    protected function createResponse(array $data = [], $class = null, $abstract = false)
    {
        if ($class === null) {
            $class = str_replace('DetailTest\\', 'Detail\\', get_class($this));
        }

        $httpResponse = $this->createHttpResponse($data);

        if ($abstract) {
            $response = $this->getMockBuilder($class)
//                ->enableOriginalConstructor()
                ->setConstructorArgs([$httpResponse])
                ->getMockForAbstractClass();

            /** @var ResponseInterface $response */
        } else {
            $response = new $class($httpResponse);
        }

        return $response;
    }

    /**
     * @param array $data
     * @return PsrResponse
     */
    private function createHttpResponse(array $data = [])
    {
        return new PsrResponse(200, [], json_encode($data));
    }
}

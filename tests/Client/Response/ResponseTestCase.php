<?php

namespace DetailTest\FileConversion\Client\Response;

use PHPUnit_Framework_TestCase as TestCase;

use GuzzleHttp\Message\Response;

use Detail\FileConversion\Client\Response\ResponseInterface;

abstract class ResponseTestCase extends TestCase
{
    /**
     * @param array $data
     * @param string $class
     * @return ResponseInterface
     */
    protected function getResponse(array $data = array(), $class = null)
    {
        if ($class === null) {
            $class = str_replace('DetailTest\\', 'Detail\\', get_class($this));
        }

        $httpResponse = $this->getHttpResponse($data);

        return $this->getMockForAbstractClass($class, array($httpResponse));
    }

    /**
     * @param array $data
     * @return Response
     */
    protected function getHttpResponse(array $data = array())
    {
        $response = $this->getMock(Response::CLASS, array(), array(), '', false);
        $response
            ->expects($this->any())
            ->method('json')
            ->will($this->returnValue($data));

        return $response;
    }
}

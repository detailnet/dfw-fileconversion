<?php

namespace DetailTest\FileConversion\Client\Response;

use Detail\FileConversion\Client\Exception;
use Detail\FileConversion\Client\Response\SaveOptions;

class SaveOptionsTest extends ResponseTestCase
{
    public function testResponseCanBeCreatedFromHttpResponse()
    {
        $response = SaveOptions::fromHttpResponse($this->getHttpResponse());

        $this->assertInstanceOf(SaveOptions::CLASS, $response);
    }

    public function testResponseCanBeCreatedFromResult()
    {
        $key = 'key';
        $value = 'value';

        $response = SaveOptions::fromResult(array($key => $value));
        $this->assertInstanceOf(SaveOptions::CLASS, $response);
        $this->assertEquals($value, $response->getResult($key));
    }

    public function testIdentifierCanBeGet()
    {
        $identifier = 'some-identifier';
        $result = array('identifier' => $identifier);

        $response = $this->getResponse($result);

        $this->assertEquals($identifier, $response->getIdentifier());

        $emptyResponse = $this->getResponse();

        $this->assertNull($emptyResponse->getIdentifier());
    }

    public function testTypeCanBeGet()
    {
        $type = 'some-type';
        $result = array('type' => $type);

        $response = $this->getResponse($result);

        $this->assertEquals($type, $response->getType());

        $emptyResponse = $this->getResponse();

        $this->setExpectedException(Exception\RuntimeException::CLASS);
        $emptyResponse->getType();
    }

    public function testParamsCanBeGet()
    {
        $params = array('key' => 'value');
        $result = array('params' => $params);

        $response = $this->getResponse($result);

        $this->assertTrue(is_array($response->getParams()));
        $this->assertEquals($params, $response->getParams());

        $emptyResponse = $this->getResponse();

        $this->setExpectedException(Exception\RuntimeException::CLASS);
        $emptyResponse->getParams();
    }

    /**
     * @param array $data
     * @param string $class
     * @return SaveOptions
     */
    protected function getResponse(array $data = array(), $class = null)
    {
        if ($class === null) {
            $class = SaveOptions::CLASS;
        }

        return parent::getResponse($data, $class);
    }
}

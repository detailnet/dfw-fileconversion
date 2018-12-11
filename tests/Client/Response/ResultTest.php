<?php

namespace DetailTest\FileConversion\Client\Response;

use Detail\FileConversion\Client\Exception;
use Detail\FileConversion\Client\Response\Result;

class ResultTest extends ResponseTestCase
{
    public function testResponseCanBeCreatedFromHttpResponse()
    {
        $response = Result::fromHttpResponse($this->getHttpResponse());

        $this->assertInstanceOf(Result::CLASS, $response);
    }

    public function testResponseCanBeCreatedFromResult()
    {
        $key = 'key';
        $value = 'value';

        $response = Result::fromResult(array($key => $value));
        $this->assertInstanceOf(Result::CLASS, $response);
        $this->assertEquals($value, $response->getResult($key));
    }

    public function testIdCanBeGet()
    {
        $id = 'some-id';
        $result = array('id' => $id);

        $response = $this->getResponse($result);

        $this->assertEquals($id, $response->getId());

        $emptyResponse = $this->getResponse();

        $this->setExpectedException(Exception\RuntimeException::CLASS);
        $emptyResponse->getId();
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

    public function testUrlCanBeGet()
    {
        $url = 'some-url';
        $result = array('url' => $url);

        $response = $this->getResponse($result);

        $this->assertEquals($url, $response->getUrl());

        $emptyResponse = $this->getResponse();

        $this->setExpectedException(Exception\RuntimeException::CLASS);
        $emptyResponse->getUrl();
    }

    public function testMetaCanBeGet()
    {
        $params = array('key' => 'value');
        $result = array('meta' => $params);

        $response = $this->getResponse($result);

        $this->assertTrue(is_array($response->getMeta()));
        $this->assertEquals($params, $response->getMeta());

        $emptyResponse = $this->getResponse();

        $this->setExpectedException(Exception\RuntimeException::CLASS);
        $emptyResponse->getMeta();
    }

    /**
     * @param array $data
     * @param string $class
     * @return Result
     */
    protected function getResponse(array $data = array(), $class = null)
    {
        if ($class === null) {
            $class = Result::CLASS;
        }

        return parent::getResponse($data, $class);
    }
}

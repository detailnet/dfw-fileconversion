<?php

namespace DetailTest\FileConversion\Client\Response;

use Detail\FileConversion\Client\Exception;
use Detail\FileConversion\Client\Response\Result;

class ResultTest extends ResponseTestCase
{
    public function testResponseCanBeCreatedFromResult()
    {
        $key = 'key';
        $value = 'value';

        $response = Result::fromResult([$key => $value]);
        $this->assertInstanceOf(Result::CLASS, $response);
        $this->assertEquals($value, $response->getResult($key));
    }

    public function testIdCanBeGet()
    {
        $id = 'some-id';
        $result = ['id' => $id];

        $response = $this->getResponse($result);

        $this->assertEquals($id, $response->getId());

        $emptyResponse = $this->getResponse();

        $this->expectException(Exception\RuntimeException::CLASS);
        $emptyResponse->getId();
    }

    public function testIdentifierCanBeGet()
    {
        $identifier = 'some-identifier';
        $result = ['identifier' => $identifier];

        $response = $this->getResponse($result);

        $this->assertEquals($identifier, $response->getIdentifier());

        $emptyResponse = $this->getResponse();

        $this->assertNull($emptyResponse->getIdentifier());
    }

    public function testUrlCanBeGet()
    {
        $url = 'some-url';
        $result = ['url' => $url];

        $response = $this->getResponse($result);

        $this->assertEquals($url, $response->getUrl());

        $emptyResponse = $this->getResponse();

        $this->expectException(Exception\RuntimeException::CLASS);
        $emptyResponse->getUrl();
    }

    public function testMetaCanBeGet()
    {
        $params = ['key' => 'value'];
        $result = ['meta' => $params];

        $response = $this->getResponse($result);

        $this->assertTrue(is_array($response->getMeta()));
        $this->assertEquals($params, $response->getMeta());

        $emptyResponse = $this->getResponse();

        $this->expectException(Exception\RuntimeException::CLASS);
        $emptyResponse->getMeta();
    }

    /**
     * @param array $data
     * @return Result
     */
    private function getResponse(array $data = [])
    {
        /** @var Result $response */
        $response = $this->createResponse($data, Result::CLASS);

        return $response;
    }
}

<?php

namespace DetailTest\FileConversion\Client\Response;

use Detail\FileConversion\Client\Exception;
use Detail\FileConversion\Client\Response\SaveOptions;

class SaveOptionsTest extends ResponseTestCase
{
    public function testResponseCanBeCreatedFromResult()
    {
        $key = 'key';
        $value = 'value';

        $response = SaveOptions::fromResult([$key => $value]);
        $this->assertInstanceOf(SaveOptions::CLASS, $response);
        $this->assertEquals($value, $response->getResult($key));
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

    public function testTypeCanBeGet()
    {
        $type = 'some-type';
        $result = ['type' => $type];

        $response = $this->getResponse($result);

        $this->assertEquals($type, $response->getType());

        $emptyResponse = $this->getResponse();

        $this->expectException(Exception\RuntimeException::CLASS);
        $emptyResponse->getType();
    }

    public function testParamsCanBeGet()
    {
        $params = ['key' => 'value'];
        $result = ['params' => $params];

        $response = $this->getResponse($result);

        $this->assertTrue(is_array($response->getParams()));
        $this->assertEquals($params, $response->getParams());

        $emptyResponse = $this->getResponse();

        $this->expectException(Exception\RuntimeException::CLASS);
        $emptyResponse->getParams();
    }

    /**
     * @param array $data
     * @return SaveOptions
     */
    private function getResponse(array $data = [])
    {
        /** @var SaveOptions $response */
        $response = $this->createResponse($data, SaveOptions::CLASS);

        return $response;
    }
}

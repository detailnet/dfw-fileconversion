<?php

namespace DetailTest\FileConversion\Client\Response;

use Detail\FileConversion\Client\Response\Result;

class ResultTest extends ResponseTestCase
{
    public function testResponseCanBeCreatedFromGuzzleCommand()
    {
        $response = Result::fromCommand(
            $this->getCommand(array('results' => array()))
        );

        $this->assertInstanceOf('Detail\FileConversion\Client\Response\Result', $response);
    }

    public function testIdCanBeGet()
    {
        $id = 'some-id';
        $result = array('id' => $id);

        $response = $this->getResponse($result);

        $this->assertEquals($id, $response->getId());

        $emptyResponse = $this->getResponse();

        $this->setExpectedException('Detail\FileConversion\Client\Exception\RuntimeException');
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

        $this->setExpectedException('Detail\FileConversion\Client\Exception\RuntimeException');
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

        $this->setExpectedException('Detail\FileConversion\Client\Exception\RuntimeException');
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
            $class = 'Detail\FileConversion\Client\Response\Result';
        }

        return parent::getResponse($data, $class);
    }
}

<?php

namespace DetailTest\FileConversion\Response;

use Detail\FileConversion\Response\Result;

class ResultTest extends ResponseTestCase
{
    public function testResponseCanBeCreatedFromGuzzleCommand()
    {
        $response = Result::fromCommand(
            $this->getCommand(array('results' => array()))
        );

        $this->assertInstanceOf('Detail\FileConversion\Response\Result', $response);
    }

    public function testIdCanBeGet()
    {
        $id = 'some-id';
        $result = array('id' => $id);

        $response = $this->getResponse($result);

        $this->assertEquals($id, $response->getId());

        $emptyResponse = $this->getResponse();

        $this->setExpectedException('Detail\FileConversion\Exception\RuntimeException');
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

        $this->setExpectedException('Detail\FileConversion\Exception\RuntimeException');
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

        $this->setExpectedException('Detail\FileConversion\Exception\RuntimeException');
        $emptyResponse->getMeta();
    }

    /**
     * @param array $data
     * @return Result
     */
    protected function getResponse(array $data = array())
    {
        return parent::getResponse('Detail\FileConversion\Response\Result', $data);
    }
}

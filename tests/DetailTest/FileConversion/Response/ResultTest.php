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
    }

    public function testIdentifierCanBeGet()
    {
        $identifier = 'some-identifier';
        $result = array('identifier' => $identifier);

        $response = $this->getResponse($result);

        $this->assertEquals($identifier, $response->getIdentifier());
    }

    public function testUrlCanBeGet()
    {
        $url = 'some-url';
        $result = array('url' => $url);

        $response = $this->getResponse($result);

        $this->assertEquals($url, $response->getUrl());
    }

    public function testMetaCanBeGet()
    {
        $params = array('key' => 'value');
        $result = array('meta' => $params);

        $response = $this->getResponse($result);

        $this->assertTrue(is_array($response->getMeta()));
        $this->assertEquals($params, $response->getMeta());
    }

    /**
     * @param array $data
     * @return Result
     */
    protected function getResponse(array $data)
    {
        return parent::getResponse('Detail\FileConversion\Response\Result', $data);
    }
}

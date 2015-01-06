<?php

namespace DetailTest\FileConversion\Response;

use Detail\FileConversion\Response\SaveOptions;

class SaveOptionsTest extends ResponseTestCase
{
    public function testResponseCanBeCreatedFromGuzzleCommand()
    {
        $response = SaveOptions::fromCommand(
            $this->getCommand(array('results' => array()))
        );

        $this->assertInstanceOf('Detail\FileConversion\Response\SaveOptions', $response);
    }

    public function testIdentifierCanBeGet()
    {
        $identifier = 'some-identifier';
        $result = array('identifier' => $identifier);

        $response = $this->getResponse($result);

        $this->assertEquals($identifier, $response->getIdentifier());
    }

    public function testTypeCanBeGet()
    {
        $type = 'some-type';
        $result = array('type' => $type);

        $response = $this->getResponse($result);

        $this->assertEquals($type, $response->getType());
    }

    public function testParamsCanBeGet()
    {
        $params = array('key' => 'value');
        $result = array('params' => $params);

        $response = $this->getResponse($result);

        $this->assertTrue(is_array($response->getParams()));
        $this->assertEquals($params, $response->getParams());
    }

    /**
     * @param array $data
     * @return SaveOptions
     */
    protected function getResponse(array $data)
    {
        return parent::getResponse('Detail\FileConversion\Response\SaveOptions', $data);
    }
}

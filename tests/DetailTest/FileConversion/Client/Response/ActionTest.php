<?php

namespace DetailTest\FileConversion\Client\Response;

use Detail\FileConversion\Client\Response\Action;

class ActionTest extends ResponseTestCase
{
    public function testResponseCanBeCreatedFromGuzzleCommand()
    {
        $response = Action::fromCommand(
            $this->getCommand(array('results' => array()))
        );

        $this->assertInstanceOf('Detail\FileConversion\Client\Response\Action', $response);
    }

    public function testNameCanBeGet()
    {
        $name = 'some-name';
        $result = array('name' => $name);

        $response = $this->getResponse($result);

        $this->assertEquals($name, $response->getName());

        $emptyResponse = $this->getResponse();

        $this->setExpectedException('Detail\FileConversion\Client\Exception\RuntimeException');
        $emptyResponse->getName();
    }

    public function testParamsCanBeGet()
    {
        $params = array('key' => 'value');
        $result = array('params' => $params);

        $response = $this->getResponse($result);

        $this->assertTrue(is_array($response->getParams()));
        $this->assertEquals($params, $response->getParams());

        $emptyResponse = $this->getResponse();

        $this->setExpectedException('Detail\FileConversion\Client\Exception\RuntimeException');
        $emptyResponse->getParams();
    }

    public function testSaveOptionsCanBeGet()
    {
        $type = 'some-type';
        $saveOptions = array('type' => $type);
        $result = array('save' => $saveOptions);

        $response = $this->getResponse($result);

        $plainSaveOptions = $response->getSaveOptions(true);

        $this->assertTrue(is_array($plainSaveOptions));
        $this->assertEquals($saveOptions, $plainSaveOptions);

        $responseSaveOptions = $response->getSaveOptions();

        $this->assertInstanceOf('Detail\FileConversion\Client\Response\SaveOptions', $responseSaveOptions);
        $this->assertEquals($type, $responseSaveOptions->getType());

        $emptyResponse = $this->getResponse();

        $this->assertNull($emptyResponse->getSaveOptions());
    }

    /**
     * @param array $data
     * @param string $class
     * @return Action
     */
    protected function getResponse(array $data = array(), $class = null)
    {
        if ($class === null) {
            $class = 'Detail\FileConversion\Client\Response\Action';
        }

        return parent::getResponse($data, $class);
    }
}

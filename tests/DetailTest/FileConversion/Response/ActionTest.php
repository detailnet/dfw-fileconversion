<?php

namespace DetailTest\FileConversion\Response;

use Detail\FileConversion\Response\Action;

class ActionTest extends ResponseTestCase
{
    public function testResponseCanBeCreatedFromGuzzleCommand()
    {
        $response = Action::fromCommand(
            $this->getCommand(array('results' => array()))
        );

        $this->assertInstanceOf('Detail\FileConversion\Response\Action', $response);
    }

    public function testNameCanBeGet()
    {
        $name = 'some-name';
        $result = array('name' => $name);

        $response = $this->getResponse($result);

        $this->assertEquals($name, $response->getName());
    }

    public function testParamsCanBeGet()
    {
        $params = array('key' => 'value');
        $result = array('params' => $params);

        $response = $this->getResponse($result);

        $this->assertTrue(is_array($response->getParams()));
        $this->assertEquals($params, $response->getParams());
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

        $this->assertInstanceOf('Detail\FileConversion\Response\SaveOptions', $responseSaveOptions);
        $this->assertEquals($type, $responseSaveOptions->getType());
    }

    /**
     * @param array $data
     * @return Action
     */
    protected function getResponse(array $data)
    {
        return parent::getResponse('Detail\FileConversion\Response\Action', $data);
    }
}

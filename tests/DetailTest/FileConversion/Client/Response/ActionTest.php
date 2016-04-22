<?php

namespace DetailTest\FileConversion\Client\Response;

use Detail\FileConversion\Client\Exception;
use Detail\FileConversion\Client\Response\Action;
use Detail\FileConversion\Client\Response\SaveOptions;

class ActionTest extends ResponseTestCase
{
    public function testResponseCanBeCreatedFromHttpResponse()
    {
        $response = Action::fromHttpResponse($this->getHttpResponse());

        $this->assertInstanceOf(Action::CLASS, $response);
    }

    public function testResponseCanBeCreatedFromResult()
    {
        $key = 'key';
        $value = 'value';

        $response = Action::fromResult(array($key => $value));
        $this->assertInstanceOf(Action::CLASS, $response);
        $this->assertEquals($value, $response->getResult($key));
    }

    public function testNameCanBeGet()
    {
        $name = 'some-name';
        $result = array('name' => $name);

        $response = $this->getResponse($result);

        $this->assertEquals($name, $response->getName());

        $emptyResponse = $this->getResponse();

        $this->setExpectedException(Exception\RuntimeException::CLASS);
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

        $this->setExpectedException(Exception\RuntimeException::CLASS);
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

        $this->assertInstanceOf(SaveOptions::CLASS, $responseSaveOptions);
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
            $class = Action::CLASS;
        }

        return parent::getResponse($data, $class);
    }
}

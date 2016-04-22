<?php

namespace DetailTest\FileConversion\Client\Response;

use DateTime;

use Detail\FileConversion\Client\Exception;
use Detail\FileConversion\Client\Response\NotificationCall;

class NotificationCallTest extends ResponseTestCase
{
    public function testResponseCanBeCreatedFromHttpResponse()
    {
        $response = NotificationCall::fromHttpResponse($this->getHttpResponse());

        $this->assertInstanceOf(NotificationCall::CLASS, $response);
    }

    public function testResponseCanBeCreatedFromResult()
    {
        $key = 'key';
        $value = 'value';

        $response = NotificationCall::fromResult(array($key => $value));
        $this->assertInstanceOf(NotificationCall::CLASS, $response);
        $this->assertEquals($value, $response->getResult($key));
    }

    public function testSentOnCanBeGet()
    {
        $date = new DateTime();
        $result = array('sent_on' => $date->format('c'));

        $response = $this->getResponse($result);

        $this->assertEquals($date, $response->getSentOn());

        $emptyResponse = $this->getResponse();

        $this->setExpectedException(Exception\RuntimeException::CLASS);
        $emptyResponse->getSentOn();
    }

    public function testSuccessCanBeGet()
    {
        $success = true;
        $result = array('success' => $success);

        $response = $this->getResponse($result);

        $this->assertEquals($success, $response->isSuccess());

        $emptyResponse = $this->getResponse();

        $this->setExpectedException(Exception\RuntimeException::CLASS);
        $emptyResponse->isSuccess();
    }

    /**
     * @param array $data
     * @param string $class
     * @return NotificationCall
     */
    protected function getResponse(array $data = array(), $class = null)
    {
        if ($class === null) {
            $class = NotificationCall::CLASS;
        }

        return parent::getResponse($data, $class);
    }
}

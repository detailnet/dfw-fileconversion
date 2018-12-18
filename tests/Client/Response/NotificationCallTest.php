<?php

namespace DetailTest\FileConversion\Client\Response;

use DateTime;

use Detail\FileConversion\Client\Exception;
use Detail\FileConversion\Client\Response\NotificationCall;

class NotificationCallTest extends ResponseTestCase
{
    public function testResponseCanBeCreatedFromResult()
    {
        $key = 'key';
        $value = 'value';

        $response = NotificationCall::fromResult([$key => $value]);
        $this->assertInstanceOf(NotificationCall::CLASS, $response);
        $this->assertEquals($value, $response->getResult($key));
    }

    public function testSentOnCanBeGet()
    {
        $date = new DateTime();
        $result = ['sent_on' => $date->format('c')];

        $response = $this->getResponse($result);

        $this->assertEquals($date, $response->getSentOn());

        $emptyResponse = $this->getResponse();

        $this->expectException(Exception\RuntimeException::CLASS);
        $emptyResponse->getSentOn();
    }

    public function testSuccessCanBeGet()
    {
        $success = true;
        $result = ['success' => $success];

        $response = $this->getResponse($result);

        $this->assertEquals($success, $response->isSuccess());

        $emptyResponse = $this->getResponse();

        $this->expectException(Exception\RuntimeException::CLASS);
        $emptyResponse->isSuccess();
    }

    /**
     * @param array $data
     * @return NotificationCall
     */
    private function getResponse(array $data = [])
    {
        /** @var NotificationCall $response */
        $response = $this->createResponse($data, NotificationCall::CLASS);

        return $response;
    }
}

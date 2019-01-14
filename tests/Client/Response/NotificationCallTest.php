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
        $formattedDate = $date->format('c');
        $result = ['sent_on' => $formattedDate];

        $response = $this->getResponse($result);

        $this->assertEquals($formattedDate, $response->getSentOn()->format('c'));

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

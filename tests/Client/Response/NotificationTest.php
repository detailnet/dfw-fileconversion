<?php

namespace DetailTest\FileConversion\Client\Response;

use Detail\FileConversion\Client\Exception;
use Detail\FileConversion\Client\Response\Notification;
use Detail\FileConversion\Client\Response\NotificationCall;

class NotificationTest extends ResponseTestCase
{
    public function testResponseCanBeCreatedFromResult()
    {
        $key = 'key';
        $value = 'value';

        $response = Notification::fromResult([$key => $value]);
        $this->assertInstanceOf(Notification::CLASS, $response);
        $this->assertEquals($value, $response->getResult($key));
    }

    public function testTypeCanBeGet()
    {
        $type = 'some-type';
        $result = ['type' => $type];

        $response = $this->getResponse($result);

        $this->assertEquals($type, $response->getType());

        $emptyResponse = $this->getResponse();

        $this->expectException(Exception\RuntimeException::CLASS);
        $emptyResponse->getType();
    }

    public function testParamsCanBeGet()
    {
        $params = ['key' => 'value'];
        $result = ['params' => $params];

        $response = $this->getResponse($result);

        $this->assertTrue(is_array($response->getParams()));
        $this->assertEquals($params, $response->getParams());

        $emptyResponse = $this->getResponse();

        $this->expectException(Exception\RuntimeException::CLASS);
        $emptyResponse->getParams();
    }

    public function testCallsCanBeGet()
    {
        $success = true;
        $calls = [['success' => $success]];
        $result = ['calls' => $calls];

        $response = $this->getResponse($result);

        $plainCalls = $response->getCalls(true);

        $this->assertTrue(is_array($plainCalls));
        $this->assertEquals($calls, $plainCalls);

        $responseCalls = $response->getCalls();

        /** @var NotificationCall $responseCall */
        $responseCall = $responseCalls[0];

        $this->assertTrue(is_array($responseCalls));
        $this->assertCount(1, $responseCalls);
        $this->assertInstanceOf(NotificationCall::CLASS, $responseCall);
        $this->assertEquals($success, $responseCall->isSuccess());

        $emptyResponse = $this->getResponse();

        $this->assertTrue(is_array($emptyResponse->getCalls()));
        $this->assertEmpty($emptyResponse->getCalls());
    }

    /**
     * @param array $data
     * @return Notification
     */
    private function getResponse(array $data = [])
    {
        /** @var Notification $response */
        $response = $this->createResponse($data, Notification::CLASS);

        return $response;
    }
}

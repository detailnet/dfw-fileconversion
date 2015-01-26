<?php

namespace DetailTest\FileConversion\Client\Response;

use Detail\FileConversion\Client\Response\Notification;

class NotificationTest extends ResponseTestCase
{
    public function testResponseCanBeCreatedFromGuzzleCommand()
    {
        $response = Notification::fromCommand(
            $this->getCommand(array('notifications' => array()))
        );

        $this->assertInstanceOf('Detail\FileConversion\Client\Response\Notification', $response);
    }

    public function testTypeCanBeGet()
    {
        $type = 'some-type';
        $result = array('type' => $type);

        $response = $this->getResponse($result);

        $this->assertEquals($type, $response->getType());

        $emptyResponse = $this->getResponse();

        $this->setExpectedException('Detail\FileConversion\Client\Exception\RuntimeException');
        $emptyResponse->getType();
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

    public function testCallsCanBeGet()
    {
        $success = true;
        $calls = array(array('success' => $success));
        $result = array('calls' => $calls);

        $response = $this->getResponse($result);

        $plainCalls = $response->getCalls(true);

        $this->assertTrue(is_array($plainCalls));
        $this->assertEquals($calls, $plainCalls);

        $responseCalls = $response->getCalls();

        /** @var \Detail\FileConversion\Client\Response\NotificationCall $responseCall */
        $responseCall = $responseCalls[0];

        $this->assertTrue(is_array($responseCalls));
        $this->assertCount(1, $responseCalls);
        $this->assertInstanceOf('Detail\FileConversion\Client\Response\NotificationCall', $responseCall);
        $this->assertEquals($success, $responseCall->isSuccess());

        $emptyResponse = $this->getResponse();

        $this->assertTrue(is_array($emptyResponse->getCalls()));
        $this->assertEmpty($emptyResponse->getCalls());
    }

    /**
     * @param array $data
     * @param string $class
     * @return Notification
     */
    protected function getResponse(array $data = array(), $class = null)
    {
        if ($class === null) {
            $class = 'Detail\FileConversion\Client\Response\Notification';
        }

        return parent::getResponse($data, $class);
    }
}

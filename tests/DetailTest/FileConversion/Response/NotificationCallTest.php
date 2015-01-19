<?php

namespace DetailTest\FileConversion\Response;

use DateTime;

use Detail\FileConversion\Response\NotificationCall;

class NotificationCallTest extends ResponseTestCase
{
    public function testResponseCanBeCreatedFromGuzzleCommand()
    {
        $response = NotificationCall::fromCommand(
            $this->getCommand(array('results' => array()))
        );

        $this->assertInstanceOf('Detail\FileConversion\Response\NotificationCall', $response);
    }

    public function testSentOnCanBeGet()
    {
        $date = new DateTime();
        $result = array('sent_on' => $date->format('c'));

        $response = $this->getResponse($result);

        $this->assertEquals($date, $response->getSentOn());

        $emptyResponse = $this->getResponse();

        $this->setExpectedException('Detail\FileConversion\Exception\RuntimeException');
        $emptyResponse->getSentOn();
    }

    public function testSuccessCanBeGet()
    {
        $success = true;
        $result = array('success' => $success);

        $response = $this->getResponse($result);

        $this->assertEquals($success, $response->isSuccess());

        $emptyResponse = $this->getResponse();

        $this->setExpectedException('Detail\FileConversion\Exception\RuntimeException');
        $emptyResponse->isSuccess();
    }

    /**
     * @param array $data
     * @return NotificationCall
     */
    protected function getResponse(array $data = array())
    {
        return parent::getResponse('Detail\FileConversion\Response\NotificationCall', $data);
    }
}

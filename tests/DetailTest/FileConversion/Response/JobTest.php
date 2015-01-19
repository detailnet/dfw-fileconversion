<?php

namespace DetailTest\FileConversion\Response;

use DateTime;

use Detail\FileConversion\Response\Job;

class JobTest extends ResponseTestCase
{
    public function testResponseCanBeCreatedFromGuzzleCommand()
    {
        $response = Job::fromCommand(
            $this->getCommand(array('results' => array()))
        );

        $this->assertInstanceOf('Detail\FileConversion\Response\Job', $response);
    }

    public function testIdCanBeGet()
    {
        $id = 'some-id';
        $result = array('id' => $id);

        $response = $this->getResponse($result);

        $this->assertEquals($id, $response->getId());

        $emptyResponse = $this->getResponse();

        $this->setExpectedException('Detail\FileConversion\Exception\RuntimeException');
        $emptyResponse->getId();
    }

    public function testSourceUrlBeGet()
    {
        $url = 'some-id';
        $result = array('source_url' => $url);

        $response = $this->getResponse($result);

        $this->assertEquals($url, $response->getSourceUrl());

        $emptyResponse = $this->getResponse();

        $this->setExpectedException('Detail\FileConversion\Exception\RuntimeException');
        $emptyResponse->getSourceUrl();
    }

    public function testSourceMetaCanBeGet()
    {
        $meta = array('key' => 'value');
        $result = array('source_meta' => $meta);

        $response = $this->getResponse($result);

        $this->assertEquals($meta, $response->getSourceMeta(true));

        $emptyResponse = $this->getResponse();

        $this->setExpectedException('Detail\FileConversion\Exception\RuntimeException');
        $emptyResponse->getSourceMeta();
    }

    public function testStatusCanBeGet()
    {
        $status = 'some-status';
        $result = array('status' => $status);

        $response = $this->getResponse($result);

        $this->assertEquals($status, $response->getStatus());

        $emptyResponse = $this->getResponse();

        $this->setExpectedException('Detail\FileConversion\Exception\RuntimeException');
        $emptyResponse->getStatus();
    }

    public function testActionCountCanBeGet()
    {
        $count = 1;
        $result = array('action_count' => $count);

        $response = $this->getResponse($result);

        $this->assertEquals($count, $response->getActionCount());

        $emptyResponse = $this->getResponse();

        $this->setExpectedException('Detail\FileConversion\Exception\RuntimeException');
        $emptyResponse->getActionCount();
    }

    public function testActionsCanBeGet()
    {
        $name = 'some-action';
        $actions = array(array('name' => $name));
        $result = array('actions' => $actions);

        $response = $this->getResponse($result);

        $plainActions = $response->getActions(true);

        $this->assertTrue(is_array($plainActions));
        $this->assertEquals($actions, $plainActions);

        $responseActions = $response->getActions();

        /** @var \Detail\FileConversion\Response\Action $responseAction */
        $responseAction = $responseActions[0];

        $this->assertTrue(is_array($responseActions));
        $this->assertCount(1, $responseActions);
        $this->assertInstanceOf('Detail\FileConversion\Response\Action', $responseAction);
        $this->assertEquals($name, $responseAction->getName());

        $emptyResponse = $this->getResponse();

        $this->setExpectedException('Detail\FileConversion\Exception\RuntimeException');
        $emptyResponse->getActions();
    }

    public function testNotificationCountCanBeGet()
    {
        $count = 1;
        $result = array('notification_count' => $count);

        $response = $this->getResponse($result);

        $this->assertEquals($count, $response->getNotificationCount());

        $emptyResponse = $this->getResponse();

        $this->setExpectedException('Detail\FileConversion\Exception\RuntimeException');
        $emptyResponse->getNotificationCount();
    }

    public function testNotificationCanBeGet()
    {
        $type = 'some-notification';
        $notifications = array(array('type' => $type));
        $result = array('notifications' => $notifications);

        $response = $this->getResponse($result);

        $plainNotifications = $response->getNotifications(true);

        $this->assertTrue(is_array($plainNotifications));
        $this->assertEquals($notifications, $plainNotifications);

        $responseNotifications = $response->getNotifications();

        /** @var \Detail\FileConversion\Response\Notification $responseNotification */
        $responseNotification = $responseNotifications[0];

        $this->assertTrue(is_array($responseNotifications));
        $this->assertCount(1, $responseNotifications);
        $this->assertInstanceOf('Detail\FileConversion\Response\Notification', $responseNotification);
        $this->assertEquals($type, $responseNotification->getType());

        $emptyResponse = $this->getResponse();

        $this->assertTrue(is_array($emptyResponse->getNotifications()));
        $this->assertEmpty($emptyResponse->getNotifications());
    }

    public function testResultCountCanBeGet()
    {
        $count = 1;
        $result = array('result_count' => $count);

        $response = $this->getResponse($result);

        $this->assertEquals($count, $response->getResultCount());

        $emptyResponse = $this->getResponse();

        $this->setExpectedException('Detail\FileConversion\Exception\RuntimeException');
        $emptyResponse->getResultCount();
    }

    public function testResultsCanBeGet()
    {
        $identifier = 'some-identifier';
        $results = array(array('identifier' => $identifier));
        $result = array('results' => $results);

        $response = $this->getResponse($result);

        $plainResults = $response->getResults(true);

        $this->assertTrue(is_array($plainResults));
        $this->assertEquals($results, $plainResults);

        $responseResults = $response->getResults();

        /** @var \Detail\FileConversion\Response\Result $responseResult */
        $responseResult = $responseResults[0];

        $this->assertTrue(is_array($responseResults));
        $this->assertCount(1, $responseResults);
        $this->assertInstanceOf('Detail\FileConversion\Response\Result', $responseResult);
        $this->assertEquals($identifier, $responseResult->getIdentifier());

        $emptyResponse = $this->getResponse();

        $this->assertTrue(is_array($emptyResponse->getResults()));
        $this->assertEmpty($emptyResponse->getResults());
    }

    public function testSubmittedOnCanBeGet()
    {
        $date = new DateTime();
        $result = array('submitted_on' => $date->format('c'));

        $response = $this->getResponse($result);

        $this->assertEquals($date, $response->getSubmittedOn());

        $emptyResponse = $this->getResponse();

        $this->setExpectedException('Detail\FileConversion\Exception\RuntimeException');
        $emptyResponse->getSubmittedOn();
    }

    public function testProcessingStartedOnCanBeGet()
    {
        $date = new DateTime();
        $result = array('processing_started_on' => $date->format('c'));

        $response = $this->getResponse($result);

        $this->assertEquals($date, $response->getProcessingStartedOn());

        $emptyResponse = $this->getResponse();

        $this->assertNull($emptyResponse->getProcessingStartedOn());
    }

    public function testProcessingCompletedOnCanBeGet()
    {
        $date = new DateTime();
        $result = array('processing_completed_on' => $date->format('c'));

        $response = $this->getResponse($result);

        $this->assertEquals($date, $response->getProcessingCompletedOn());

        $emptyResponse = $this->getResponse();

        $this->assertNull($emptyResponse->getProcessingCompletedOn());
    }

    public function testCompletedOnCanBeGet()
    {
        $date = new DateTime();
        $result = array('completed_on' => $date->format('c'));

        $response = $this->getResponse($result);

        $this->assertEquals($date, $response->getCompletedOn());

        $emptyResponse = $this->getResponse();

        $this->assertNull($emptyResponse->getCompletedOn());
    }

    /**
     * @param array $data
     * @return Job
     */
    protected function getResponse(array $data = array())
    {
        return parent::getResponse('Detail\FileConversion\Response\Job', $data);
    }
}

<?php

namespace DetailTest\FileConversion\Client\Response;

use DateTime;

use Detail\FileConversion\Client\Exception;
use Detail\FileConversion\Client\Response\Action;
use Detail\FileConversion\Client\Response\Job;
use Detail\FileConversion\Client\Response\Notification;
use Detail\FileConversion\Client\Response\Result;

class JobTest extends ResponseTestCase
{
    public function testResponseCanBeCreatedFromHttpResponse()
    {
        $response = Job::fromHttpResponse($this->getHttpResponse());

        $this->assertInstanceOf(Job::CLASS, $response);
    }

    public function testResponseCanBeCreatedFromResult()
    {
        $key = 'key';
        $value = 'value';

        $response = Job::fromResult(array($key => $value));
        $this->assertInstanceOf(Job::CLASS, $response);
        $this->assertEquals($value, $response->getResult($key));
    }

    public function testIdCanBeGet()
    {
        $id = 'some-id';
        $result = array('id' => $id);

        $response = $this->getResponse($result);

        $this->assertEquals($id, $response->getId());

        $emptyResponse = $this->getResponse();

        $this->setExpectedException(Exception\RuntimeException::CLASS);
        $emptyResponse->getId();
    }

    public function testSourceUrlCanBeGet()
    {
        $url = 'some-id';
        $result = array('source_url' => $url);

        $response = $this->getResponse($result);

        $this->assertEquals($url, $response->getSourceUrl());

        $emptyResponse = $this->getResponse();

        $this->setExpectedException(Exception\RuntimeException::CLASS);
        $emptyResponse->getSourceUrl();
    }

    public function testSourceMetaCanBeGet()
    {
        $meta = array('key' => 'value');
        $result = array('source_meta' => $meta);

        $response = $this->getResponse($result);

        $this->assertEquals($meta, $response->getSourceMeta());

        $emptyResponse = $this->getResponse();

        $this->setExpectedException(Exception\RuntimeException::CLASS);
        $emptyResponse->getSourceMeta();
    }

    public function testStatusCanBeGet()
    {
        $status = 'some-status';
        $result = array('status' => $status);

        $response = $this->getResponse($result);

        $this->assertEquals($status, $response->getStatus());

        $emptyResponse = $this->getResponse();

        $this->setExpectedException(Exception\RuntimeException::CLASS);
        $emptyResponse->getStatus();
    }

    public function testActionCountCanBeGet()
    {
        $count = 1;
        $result = array('action_count' => $count);

        $response = $this->getResponse($result);

        $this->assertEquals($count, $response->getActionCount());

        $emptyResponse = $this->getResponse();

        $this->setExpectedException(Exception\RuntimeException::CLASS);
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

        /** @var Action $responseAction */
        $responseAction = $responseActions[0];

        $this->assertTrue(is_array($responseActions));
        $this->assertCount(1, $responseActions);
        $this->assertInstanceOf(Action::CLASS, $responseAction);
        $this->assertEquals($name, $responseAction->getName());

        $emptyResponse = $this->getResponse();

        $this->setExpectedException(Exception\RuntimeException::CLASS);
        $emptyResponse->getActions();
    }

    public function testNotificationCountCanBeGet()
    {
        $count = 1;
        $result = array('notification_count' => $count);

        $response = $this->getResponse($result);

        $this->assertEquals($count, $response->getNotificationCount());

        $emptyResponse = $this->getResponse();

        $this->setExpectedException(Exception\RuntimeException::CLASS);
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

        /** @var Notification $responseNotification */
        $responseNotification = $responseNotifications[0];

        $this->assertTrue(is_array($responseNotifications));
        $this->assertCount(1, $responseNotifications);
        $this->assertInstanceOf(Notification::CLASS, $responseNotification);
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

        $this->setExpectedException(Exception\RuntimeException::CLASS);
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

        /** @var Result $responseResult */
        $responseResult = $responseResults[0];

        $this->assertTrue(is_array($responseResults));
        $this->assertCount(1, $responseResults);
        $this->assertInstanceOf(Result::CLASS, $responseResult);
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

        $this->setExpectedException(Exception\RuntimeException::CLASS);
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
     * @param string $class
     * @return Job
     */
    protected function getResponse(array $data = array(), $class = null)
    {
        if ($class === null) {
            $class = Job::CLASS;
        }

        return parent::getResponse($data, $class);
    }
}

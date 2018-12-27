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
    public function testResponseCanBeCreatedFromResult()
    {
        $key = 'key';
        $value = 'value';

        $response = Job::fromResult([$key => $value]);
        $this->assertInstanceOf(Job::CLASS, $response);
        $this->assertEquals($value, $response->getResult($key));
    }

    public function testIdCanBeGet()
    {
        $id = 'some-id';
        $result = ['id' => $id];

        $response = $this->getResponse($result);

        $this->assertEquals($id, $response->getId());

        $emptyResponse = $this->getResponse();

        $this->expectException(Exception\RuntimeException::CLASS);
        $emptyResponse->getId();
    }

    public function testSourceUrlCanBeGet()
    {
        $url = 'some-id';
        $result = ['source_url' => $url];

        $response = $this->getResponse($result);

        $this->assertEquals($url, $response->getSourceUrl());

        $emptyResponse = $this->getResponse();

        $this->expectException(Exception\RuntimeException::CLASS);
        $emptyResponse->getSourceUrl();
    }

    public function testSourceMetaCanBeGet()
    {
        $meta = ['key' => 'value'];
        $result = ['source_meta' => $meta];

        $response = $this->getResponse($result);

        $this->assertEquals($meta, $response->getSourceMeta());

        $emptyResponse = $this->getResponse();

        $this->expectException(Exception\RuntimeException::CLASS);
        $emptyResponse->getSourceMeta();
    }

    public function testStatusCanBeGet()
    {
        $status = 'some-status';
        $result = ['status' => $status];

        $response = $this->getResponse($result);

        $this->assertEquals($status, $response->getStatus());

        $emptyResponse = $this->getResponse();

        $this->expectException(Exception\RuntimeException::CLASS);
        $emptyResponse->getStatus();
    }

    public function testActionCountCanBeGet()
    {
        $count = 1;
        $result = ['action_count' => $count];

        $response = $this->getResponse($result);

        $this->assertEquals($count, $response->getActionCount());

        $emptyResponse = $this->getResponse();

        $this->expectException(Exception\RuntimeException::CLASS);
        $emptyResponse->getActionCount();
    }

    public function testActionsCanBeGet()
    {
        $name = 'some-action';
        $actions = [['name' => $name]];
        $result = ['actions' => $actions];

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

        $this->expectException(Exception\RuntimeException::CLASS);
        $emptyResponse->getActions();
    }

    public function testNotificationCountCanBeGet()
    {
        $count = 1;
        $result = ['notification_count' => $count];

        $response = $this->getResponse($result);

        $this->assertEquals($count, $response->getNotificationCount());

        $emptyResponse = $this->getResponse();

        $this->expectException(Exception\RuntimeException::CLASS);
        $emptyResponse->getNotificationCount();
    }

    public function testNotificationCanBeGet()
    {
        $type = 'some-notification';
        $notifications = [['type' => $type]];
        $result = ['notifications' => $notifications];

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
        $result = ['result_count' => $count];

        $response = $this->getResponse($result);

        $this->assertEquals($count, $response->getResultCount());

        $emptyResponse = $this->getResponse();

        $this->expectException(Exception\RuntimeException::CLASS);
        $emptyResponse->getResultCount();
    }

    public function testResultsCanBeGet()
    {
        $identifier = 'some-identifier';
        $results = [['identifier' => $identifier]];
        $result = ['results' => $results];

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
        $formattedDate = $date->format('c');
        $result = ['submitted_on' => $formattedDate];

        $response = $this->getResponse($result);

        $this->assertEquals($formattedDate, $response->getSubmittedOn()->format('c'));

        $emptyResponse = $this->getResponse();

        $this->expectException(Exception\RuntimeException::CLASS);
        $emptyResponse->getSubmittedOn();
    }

    public function testProcessingStartedOnCanBeGet()
    {
        $date = new DateTime();
        $formattedDate = $date->format('c');
        $result = ['processing_started_on' => $formattedDate];

        $response = $this->getResponse($result);

        $this->assertEquals($formattedDate, $response->getProcessingStartedOn()->format('c'));

        $emptyResponse = $this->getResponse();

        $this->assertNull($emptyResponse->getProcessingStartedOn());
    }

    public function testProcessingCompletedOnCanBeGet()
    {
        $date = new DateTime();
        $formattedDate = $date->format('c');
        $result = ['processing_completed_on' => $formattedDate];

        $response = $this->getResponse($result);

        $this->assertEquals($formattedDate, $response->getProcessingCompletedOn()->format('c'));

        $emptyResponse = $this->getResponse();

        $this->assertNull($emptyResponse->getProcessingCompletedOn());
    }

    public function testCompletedOnCanBeGet()
    {
        $date = new DateTime();
        $formattedDate = $date->format('c');
        $result = ['completed_on' => $formattedDate];

        $response = $this->getResponse($result);

        $this->assertEquals($formattedDate, $response->getCompletedOn()->format('c'));

        $emptyResponse = $this->getResponse();

        $this->assertNull($emptyResponse->getCompletedOn());
    }

    /**
     * @param array $data
     * @return Job
     */
    private function getResponse(array $data = [])
    {
        /** @var Job $response */
        $response = $this->createResponse($data, Job::CLASS);

        return $response;
    }
}

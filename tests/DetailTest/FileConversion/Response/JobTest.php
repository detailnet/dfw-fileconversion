<?php

namespace DetailTest\FileConversion\Response;

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
    }

    public function testSourceUrlBeGet()
    {
        $url = 'some-id';
        $result = array('source_url' => $url);

        $response = $this->getResponse($result);

        $this->assertEquals($url, $response->getSourceUrl());
    }

    public function testSourceMetaCanBeGet()
    {
        $meta = array('key' => 'value');
        $result = array('source_meta' => $meta);

        $response = $this->getResponse($result);

        $this->assertEquals($meta, $response->getSourceMeta(true));
    }

    public function testStatusCanBeGet()
    {
        $status = 'some-status';
        $result = array('status' => $status);

        $response = $this->getResponse($result);

        $this->assertEquals($status, $response->getStatus());
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
    }

    /**
     * @param array $data
     * @return Job
     */
    protected function getResponse(array $data)
    {
        return parent::getResponse('Detail\FileConversion\Response\Job', $data);
    }
}

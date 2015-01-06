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

    public function testActionsCanBeGet()
    {
        $actions = array(array('identifier' => 'some-identifier'));
        $result = array('actions' => $actions);

        $response = $this->getResponse($result);

        $this->assertEquals($actions, $response->getActions(true));
    }

    public function testSourceMetaCanBeGet()
    {
        $meta = array(array('key' => 'value'));
        $result = array('source_meta' => $meta);

        $response = $this->getResponse($result);

        $this->assertEquals($meta, $response->getSourceMeta(true));
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

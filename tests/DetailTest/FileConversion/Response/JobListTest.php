<?php

namespace DetailTest\FileConversion\Response;

use Detail\FileConversion\Response\JobList;

class JobListTest extends ResponseTestCase
{
    public function testResponseCanBeCreatedFromGuzzleCommand()
    {
        $response = JobList::fromCommand(
            $this->getCommand(array('results' => array()))
        );

        $this->assertInstanceOf('Detail\FileConversion\Response\JobList', $response);
    }

    public function testItemsCanBeGet()
    {
        $id = 'some-id';
        $jobs = array(array('id' => $id));
        $result = array(
            'jobs' => $jobs,
            'page_count' => 1,
            'page_size' => 10,
            'total_items' => 20,
        );

        $response = $this->getResponse($result);

        $plainJobs = $response->getItems(true);

        $this->assertTrue(is_array($plainJobs));
        $this->assertEquals($jobs, $plainJobs);

        $responseJobs = $response->getItems();

        /** @var \Detail\FileConversion\Response\Job $responseJob */
        $responseJob = $responseJobs[0];

        $this->assertTrue(is_array($responseJobs));
        $this->assertCount(1, $responseJobs);
        $this->assertInstanceOf('Detail\FileConversion\Response\Job', $responseJob);
        $this->assertEquals($id, $responseJob->getId());
        $this->assertEquals($result['page_count'], $response->getPageCount());
        $this->assertEquals($result['page_size'], $response->getPageSize());
        $this->assertEquals(count($responseJobs), $response->getItemCount());
        $this->assertEquals($result['total_items'], $response->getTotalItemCount());
    }

    /**
     * @param array $data
     * @return JobList
     */
    protected function getResponse(array $data)
    {
        return parent::getResponse('Detail\FileConversion\Response\JobList', $data);
    }
}

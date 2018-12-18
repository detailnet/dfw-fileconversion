<?php

namespace DetailTest\FileConversion\Client\Response;

use Detail\FileConversion\Client\Exception;
use Detail\FileConversion\Client\Response\Job;
use Detail\FileConversion\Client\Response\JobList;

class JobListTest extends ResponseTestCase
{
    public function testResponseCanBeCreatedFromResult()
    {
        $key = 'key';
        $value = 'value';

        $response = JobList::fromResult([$key => $value]);
        $this->assertInstanceOf(JobList::CLASS, $response);
        $this->assertEquals($value, $response->getResult($key));
    }

    public function testItemsCanBeGet()
    {
        $id = 'some-id';
        $jobs = [['id' => $id]];
        $result = [
            'jobs' => $jobs,
            'page_count' => 1,
            'page_size' => 10,
            'total_items' => 20,
        ];

        $response = $this->getResponse($result);

        $plainJobs = $response->getItems(true);

        $this->assertTrue(is_array($plainJobs));
        $this->assertEquals($jobs, $plainJobs);

        $responseJobs = $response->getItems();

        /** @var Job $responseJob */
        $responseJob = $responseJobs[0];

        $this->assertTrue(is_array($responseJobs));
        $this->assertCount(1, $responseJobs);
        $this->assertInstanceOf(Job::CLASS, $responseJob);
        $this->assertEquals($id, $responseJob->getId());
        $this->assertEquals($result['page_count'], $response->getPageCount());
        $this->assertEquals($result['page_size'], $response->getPageSize());
        $this->assertEquals(count($responseJobs), $response->getItemCount());
        $this->assertEquals($result['total_items'], $response->getTotalItemCount());

        $emptyResponse = $this->getResponse();

        $this->expectException(Exception\RuntimeException::CLASS);
        $emptyResponse->getPageCount();

        /** @todo Handle expected exceptions for other methods... */
    }

    /**
     * @param array $data
     * @return JobList
     */
    private function getResponse(array $data = [])
    {
        /** @var JobList $response */
        $response = $this->createResponse($data, JobList::CLASS);

        return $response;
    }
}

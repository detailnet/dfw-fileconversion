<?php

namespace DetailTest\FileConversion\Client\Response;

use DateTime;

use Detail\FileConversion\Client\Exception;
use Detail\FileConversion\Client\Response\BaseResponse;

class BaseResponseTest extends ResponseTestCase
{
    public function testResultCanBeGet()
    {
        $resultKey = 'key';
        $resultValue = 'value';
        $result = [$resultKey => $resultValue];

        $response = $this->getResponse($result);

        $this->assertEquals($result, $response->getResult());
        $this->assertArrayHasKey($resultKey, $response->getResult());
        $this->assertEquals($resultValue, $response->getResult($resultKey));
        $this->assertNull($response->getResult('non_existing_key', false));

        $this->expectException(Exception\RuntimeException::CLASS);
        $response->getResult('non_existing_key');
    }

    public function testDateResultCanBeGet()
    {
        $resultKey = 'date';
        $resultValue = '2015-01-19T15:08:28+0100';
        $result = [$resultKey => $resultValue];

        $response = $this->getResponse($result);

        $this->assertInstanceOf('DateTime', $response->getDateResult($resultKey));
        $this->assertEquals(new DateTime($resultValue), $response->getDateResult($resultKey));
        $this->assertNull($response->getDateResult('non_existing_key', false));

        $this->expectException(Exception\RuntimeException::CLASS);
        $response->getDateResult('non_existing_key');
    }

    /**
     * @param array $data
     * @return BaseResponse
     */
    private function getResponse(array $data = [])
    {
        /** @var BaseResponse $response */
        $response = $this->createResponse($data, BaseResponse::CLASS, true);

        return $response;
    }
}

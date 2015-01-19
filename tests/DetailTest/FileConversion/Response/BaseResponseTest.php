<?php

namespace DetailTest\FileConversion\Response;

use DateTime;

use Detail\FileConversion\Response\BaseResponse;

class BaseResponseTest extends ResponseTestCase
{
    public function testResultCanBeGet()
    {
        $resultKey = 'key';
        $resultValue = 'value';
        $result = array($resultKey => $resultValue);

        $response = $this->getResponse($result);

        $this->assertEquals($result, $response->getResult());
        $this->assertArrayHasKey($resultKey, $response->getResult());
        $this->assertEquals($resultValue, $response->getResult($resultKey));
        $this->assertNull($response->getResult('non-existing-key', false));

        $this->setExpectedException('Detail\FileConversion\Exception\RuntimeException');
        $response->getResult('non-existing-key');
    }

    public function testDateResultCanBeGet()
    {
        $resultKey = 'date';
        $resultValue = '2015-01-19T15:08:28+0100';
        $result = array($resultKey => $resultValue);

        $response = $this->getResponse($result);

        $this->assertInstanceOf('DateTime', $response->getDateResult($resultKey));
        $this->assertEquals(new DateTime($resultValue), $response->getDateResult($resultKey));
        $this->assertNull($response->getDateResult('non-existing-key', false));

        $this->setExpectedException('Detail\FileConversion\Exception\RuntimeException');
        $response->getDateResult('non-existing-key');
    }

    /**
     * @param array $data
     * @return BaseResponse
     */
    protected function getResponse(array $data)
    {
        return parent::getResponse('Detail\FileConversion\Response\BaseResponse', $data);
    }
}

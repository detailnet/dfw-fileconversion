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

        $this->setExpectedException('Detail\FileConversion\Exception\RuntimeException');
        Job::fromCommand($this->getCommand(array()));
    }

    public function testImagesCanBeGet()
    {
        $images = array(array('image_identifier' => 'some-image-identifier'));
        $result = array('images' => $images);

        $response = $this->getResponse($result);

        $this->assertEquals($images, $response->getImages());
    }

    public function testOriginalMetaCanBeGet()
    {
        $meta = array(array('key' => 'value'));
        $result = array('original_meta' => $meta);

        $response = $this->getResponse($result);

        $this->assertEquals($meta, $response->getOriginalMeta());
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

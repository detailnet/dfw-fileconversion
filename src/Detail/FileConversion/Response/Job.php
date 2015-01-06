<?php

namespace  Detail\FileConversion\Response;

class Job extends BaseResponse
{
    /**
     * @return string
     */
    public function getId()
    {
        return $this->getResult('id');
    }

    /**
     * @return array
     */
    public function getImages()
    {
        return $this->getResult('images');
    }

    /**
     * @return array
     */
    public function getOriginalMeta()
    {
        return $this->getResult('original_meta');
    }
}

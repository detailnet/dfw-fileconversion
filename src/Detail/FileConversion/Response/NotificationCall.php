<?php

namespace Detail\FileConversion\Response;

class NotificationCall extends BaseResponse
{
    /**
     * @return string
     */
    public function getSentOn()
    {
        return $this->getDateResult('sent_on');
    }

    /**
     * @return boolean
     */
    public function isSuccess()
    {
        return (boolean) $this->getResult('success');
    }
}

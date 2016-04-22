<?php

namespace Detail\FileConversion\Client\Response;

class Notification extends BaseResponse
{
    /**
     * @var array
     */
    protected $calls;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->getResult('type');
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->getResult('params');
    }

    /**
     * @param boolean $asPlainResult
     * @return NotificationCall[]|array
     */
    public function getCalls($asPlainResult = false)
    {
        return $this->getSubResults('calls', array($this, 'createCall'), $asPlainResult, false);
    }

    /**
     * @param array $data
     * @return NotificationCall
     */
    protected function createCall(array $data)
    {
        return NotificationCall::fromResult($data);
    }
}

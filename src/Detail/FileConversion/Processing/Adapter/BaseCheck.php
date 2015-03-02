<?php

namespace Detail\FileConversion\Processing\Adapter;

abstract class BaseCheck
{
    /**
     * @var boolean
     */
    protected $valid;

    /**
     * @var string[]
     */
    protected $messages;

    /**
     * @param boolean $valid
     * @param string[] $messages
     */
    public function __construct($valid = true, array $messages = array())
    {
        $this->valid = (bool) $valid;
        $this->messages = $messages;
    }

    /**
     * @return boolean
     */
    public function isValid()
    {
        return $this->valid;
    }

    /**
     * @return string[]
     */
    public function getMessages()
    {
        return $this->messages;
    }
}

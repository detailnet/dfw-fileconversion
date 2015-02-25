<?php

namespace Detail\FileConversion\Processing\Support;

use Detail\FileConversion\Processing\Adapter;

class AdapterSupport
{
    /**
     * @var boolean
     */
    protected $supported;

    /**
     * @var string
     */
    protected $message;

    /**
     * @param Adapter\AdapterInterface $adapter
     * @param array $actions
     * @return self
     */
    public static function test(Adapter\AdapterInterface $adapter, array $actions)
    {
        $supported = true;
        $message = null;
        $unsupportedActions = array();

        foreach ($actions as $action) {
            if (!$adapter->supportsAction($action)) {
                $unsupportedActions[] = $action;
            }
        }

        if (count($unsupportedActions) > 0) {
            $supported = false;
            $message = 'The following actions are not supported: ' . implode(', ', $unsupportedActions);
        }

        return new static($supported, $message);
    }

    /**
     * @param boolean $supported
     * @param string $message
     */
    public function __construct($supported, $message = null)
    {
        $this->supported = (bool) $supported;

        if ($message !== null) {
            $this->message = $message;
        }
    }

    /**
     * @return boolean
     */
    public function isSupported()
    {
        return $this->supported;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}

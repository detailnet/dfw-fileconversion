<?php

namespace Detail\FileConversion\Processing\Adapter;

abstract class BaseAdapter implements
    AdapterInterface
{
    /**
     * @var string[]
     */
    protected static $supportedActions = array();

    /**
     * @var array
     */
    protected $options = array();

    /**
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * @return string[]
     */
    public static function getSupportedActions()
    {
        return static::$supportedActions;
    }

    /**
     * @param string[] $supportedActions
     */
    public static function setSupportedActions(array $supportedActions)
    {
        static::$supportedActions = $supportedActions;
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getOption($name, $default = null)
    {
        return isset($this->options[$name]) ? $this->options[$name] : $default;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * @param string $action
     * @return boolean
     */
    public function supportsAction($action)
    {
        return in_array($action, self::getSupportedActions());
    }

    /**
     * @param array $actions
     * @return array
     */
    public function supportsActions(array $actions)
    {
        foreach ($actions as $action) {
            if ($this->supportsAction($action) === false) {
                return false;
            }
        }

        return true;
    }
}

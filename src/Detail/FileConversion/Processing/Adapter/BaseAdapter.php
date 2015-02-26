<?php

namespace Detail\FileConversion\Processing\Adapter;

use Detail\FileConversion\Processing\Task;

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
        /** @todo Make sure the classes exist and point to implementations of ActionInterface */
        static::$supportedActions = $supportedActions;
    }

    /**
     * @param string $action
     * @param string $default
     * @return string|null
     */
    public static function getSupportedActionClass($action, $default = null)
    {
        $supportedActions = self::getSupportedActions();

        return isset($supportedActions[$action]) ? $supportedActions[$action] : $default;
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
        $supportedActions = self::getSupportedActions();

        return isset($supportedActions[$action]);
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

    /**
     * @param Task\TaskInterface $task
     * @return SupportCheck
     */
    public function supportsTask(Task\TaskInterface $task)
    {
        $actualActions = $this->getTaskActions($task);

        $support = new SupportCheck();
        $support->validate(array_keys($this->getSupportedActions()), $actualActions);

        return $support;
    }

    /**
     * @param Task\TaskInterface $task
     * @return ValidationCheck
     */
    public function validateTask(Task\TaskInterface $task)
    {
        $actionParams = $this->getTaskActionParams($task);
        $validation = new ValidationCheck();

        foreach ($actionParams as $action => $actualParams) {
            $actionClass = self::getSupportedActionClass($action);
            $requiredParamsProvider = array($actionClass, 'getRequiredParams');

            // We assume the action is valid when:
            // 1. We have no class that can perform the validation
            // 2. The class does not provide the required params
            if ($actionClass === null
                || !is_callable($requiredParamsProvider)
            ) {
                continue;
            }

            $requiredParams = call_user_func($requiredParamsProvider);

            $validation->validate($action, $requiredParams, $actualParams);
        }

        return $validation;
    }

    /**
     * @param Task\TaskInterface $task
     * @return array
     */
    abstract protected function getTaskActions(Task\TaskInterface $task);

    /**
     * @param Task\TaskInterface $task
     * @return array
     */
    abstract protected function getTaskActionParams(Task\TaskInterface $task);
}

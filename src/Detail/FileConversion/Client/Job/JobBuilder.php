<?php

namespace Detail\FileConversion\Client\Job;

use Detail\FileConversion\Client\Exception\RuntimeException;

class JobBuilder implements
    JobBuilderInterface
{
    /**
     * @var string
     */
    protected $jobClass = Definition\JobDefinition::CLASS;

    /**
     * @var string
     */
    protected $actionClass = Definition\ActionDefinition::CLASS;

    /**
     * @var string
     */
    protected $notificationClass = Definition\NotificationDefinition::CLASS;

    /**
     * @var array
     */
    protected $defaultOptions = array(
        'notification.type' => 'webhook',
    );

    /**
     * @return string
     */
    public function getJobClass()
    {
        return $this->jobClass;
    }

    /**
     * @param string $jobClass
     * @return JobBuilder
     */
    public function setJobClass($jobClass)
    {
        $this->jobClass = $jobClass;
        return $this;
    }

    /**
     * @return string
     */
    public function getActionClass()
    {
        return $this->actionClass;
    }

    /**
     * @param string $actionClass
     * @return JobBuilder
     */
    public function setActionClass($actionClass)
    {
        $this->actionClass = $actionClass;
        return $this;
    }

    /**
     * @return string
     */
    public function getNotificationClass()
    {
        return $this->notificationClass;
    }

    /**
     * @param string $notificationClass
     * @return JobBuilder
     */
    public function setNotificationClass($notificationClass)
    {
        $this->notificationClass = $notificationClass;
        return $this;
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getDefaultOption($name, $default = null)
    {
        return array_key_exists($name, $this->defaultOptions) ? $this->defaultOptions[$name] : $default;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return JobBuilder
     */
    public function setDefaultOption($name, $value)
    {
        $this->defaultOptions[$name] = $value;
        return $this;
    }

    /**
     * @param Definition\DefinitionInterface $definition
     * @return array
     */
    public function getDefaultOptions(Definition\DefinitionInterface $definition = null)
    {
        $options = $this->defaultOptions;

        if ($definition === null) {
            return $options;
        }

        $prefix = null;
        $prefixSeparator = '.';

        if ($definition instanceof Definition\JobDefinitionInterface) {
            $prefix = 'job';
        } elseif ($definition instanceof Definition\ActionDefinitionInterface) {
            $prefix = 'action';
        } elseif ($definition instanceof Definition\NotificationDefinitionInterface) {
            $prefix = 'notification';
        } else {
            return array();
        }

        $keyMatchesPrefix = function($key) use ($prefix, $prefixSeparator) {
            $combinedPrefix = $prefix . $prefixSeparator;

            return (strpos($key, $combinedPrefix) === 0) && (strlen($key) > strlen($combinedPrefix));
        };

        $matchingKeys = array_filter(array_keys($options), $keyMatchesPrefix);

        $matchingOptions = array_intersect_key($options, array_flip($matchingKeys));
        $matchingOptionsWithoutPrefix = array();

        $prefixLength = strlen($prefix) + strlen($prefixSeparator);

        foreach ($matchingOptions as $optionName => $optionsValue) {
            $matchingOptionsWithoutPrefix[substr($optionName, $prefixLength)] = $optionsValue;
        }

        return $matchingOptionsWithoutPrefix;
    }

    /**
     * @param array $options
     * @return JobBuilder
     */
    public function setDefaultOptions(array $options)
    {
        $this->defaultOptions = $options;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function createJob()
    {
        return $this->createDefinition(
            $this->getJobClass(),
            Definition\JobDefinitionInterface::CLASS
        );
    }

    /**
     * @inheritdoc
     */
    public function createAction()
    {
        return $this->createDefinition(
            $this->getActionClass(),
            Definition\ActionDefinitionInterface::CLASS
        );
    }

    /**
     * @inheritdoc
     */
    public function createNotification()
    {
        return $this->createDefinition(
            $this->getNotificationClass(),
            Definition\NotificationDefinitionInterface::CLASS
        );
    }

    /**
     * @param string $class
     * @param string $interface
     * @return Definition\DefinitionInterface
     */
    protected function createDefinition($class, $interface)
    {
        if (!class_exists($class)) {
            throw new RuntimeException(sprintf('Class "%s" does not exist', $class));
        }

        /** @var Definition\DefinitionInterface $definition */
        $definition = new $class();

        if (!$definition instanceof $interface) {
            throw new RuntimeException(
                sprintf('Definition of class "%s" does not implement "%s"', $class, $interface)
            );
        }

        $definition->applyOptions($this->getDefaultOptions($definition));

        return $definition;
    }
}

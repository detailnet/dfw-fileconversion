<?php

namespace Detail\FileConversion\Processing\Adapter\Blitline\Func\Script;

abstract class BaseOption implements
    OptionInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $argument;

    /**
     * @var boolean
     */
    protected $enabled = true;

    /**
     * @param string $name
     * @param string $argument
     * @param boolean $enabled
     */
    public function __construct($name, $argument, $enabled = true)
    {
        $this->setName($name);
        $this->setArgument($argument);
        $this->setEnabled($enabled);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getArgument()
    {
        return $this->argument;
    }

    /**
     * @param string $argument
     */
    public function setArgument($argument)
    {
        $this->argument = $argument;
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = (boolean) $enabled;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @return string
     */
    abstract public function toString();
}

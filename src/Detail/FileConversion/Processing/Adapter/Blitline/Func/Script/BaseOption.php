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
     * @param string $name
     * @param string $argument
     */
    public function __construct($name, $argument)
    {
        $this->setName($name);
        $this->setArgument($argument);
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

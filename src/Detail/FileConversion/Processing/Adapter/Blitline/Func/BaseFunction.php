<?php

namespace Detail\FileConversion\Processing\Adapter\Blitline\Func;

abstract class BaseFunction implements
    FunctionInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}

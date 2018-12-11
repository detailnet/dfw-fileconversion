<?php

namespace Detail\FileConversion\Processing\Action;

abstract class BaseAction implements
    ActionInterface
{
    const NAME = 'unknown';

    /**
     * @var array
     */
    protected static $requiredParams = [];

    /**
     * @return string
     */
    public static function getName()
    {
        return static::NAME;
    }

    /**
     * @return array
     */
    public static function getRequiredParams()
    {
        return static::$requiredParams;
    }
}

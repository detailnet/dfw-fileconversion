<?php

namespace Detail\FileConversion\Processing\Action;

abstract class BaseAction implements
    ActionInterface
{
    const NAME = 'unknown';

    /**
     * @var array
     */
    protected $requiredParams = array();

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
    public function getRequiredParams()
    {
        return $this->requiredParams;
    }
}

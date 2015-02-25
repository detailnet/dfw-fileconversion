<?php

namespace Detail\FileConversion\Processing\Action;

interface ActionInterface
{
    /**
     * @return string
     */
    public static function getName();

    /**
     * @return array
     */
    public function getRequiredParams();
}

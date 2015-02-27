<?php

namespace Detail\FileConversion\Processing\Adapter\Blitline\Func;

interface FunctionInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return array
     */
    public function getParams();

    /**
     * @param array $params
     * @return array
     */
    public function applyParams(array $params);
}

<?php

namespace Detail\FileConversion\Processing\Adapter\Blitline;

interface FunctionProviderInterface
{
    /**
     * @param string $action
     * @return boolean
     */
    public function hasFunction($action);

    /**
     * @param string $action
     * @param array $params
     * @return Func\FunctionInterface
     */
    public function getFunction($action, array $params = array());
}

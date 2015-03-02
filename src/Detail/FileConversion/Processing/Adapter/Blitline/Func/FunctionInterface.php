<?php

namespace Detail\FileConversion\Processing\Adapter\Blitline\Func;

interface FunctionInterface
{
    /**
     * @param string $name
     * @param array $options
     * @return self
     */
    public static function fromOptions($name, array $options);

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

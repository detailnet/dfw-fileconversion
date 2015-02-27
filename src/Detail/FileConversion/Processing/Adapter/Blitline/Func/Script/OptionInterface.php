<?php

namespace Detail\FileConversion\Processing\Adapter\Blitline\Func\Script;

interface OptionInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @return string
     */
    public function __toString();

    /**
     * @return string
     */
    public function toString();
}

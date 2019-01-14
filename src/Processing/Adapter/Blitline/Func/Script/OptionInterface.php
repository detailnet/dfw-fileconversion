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
     * @return boolean
     */
    public function isEnabled();

    /**
     * @param boolean $enabled
     */
    public function setEnabled($enabled);

    /**
     * @return string
     */
    public function toString();
}

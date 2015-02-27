<?php

namespace Detail\FileConversion\Processing\Adapter\Blitline\Func\Script;

class PlainOption extends BaseOption
{
    /**
     * @return string
     */
    public function toString()
    {
        return '-' . $this->getName();
    }
}

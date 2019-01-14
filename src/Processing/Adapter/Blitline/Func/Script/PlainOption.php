<?php

namespace Detail\FileConversion\Processing\Adapter\Blitline\Func\Script;

class PlainOption extends BaseOption
{
    const NAME = 'plain';

    /**
     * @return string
     */
    public function toString()
    {
        return '-' . $this->getArgument();
    }
}

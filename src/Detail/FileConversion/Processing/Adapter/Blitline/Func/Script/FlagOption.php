<?php

namespace Detail\FileConversion\Processing\Adapter\Blitline\Func\Script;

class FlagOption extends ValueOption
{
    const NAME = 'flag';

    /**
     * @return boolean
     */
    public function getValue()
    {
        return (boolean) parent::getValue();
    }

    /**
     * @return string
     */
    public function toString()
    {
        if (!$this->getValue()) {
            return '';
        }

        return '-' . $this->getArgument();
    }
}

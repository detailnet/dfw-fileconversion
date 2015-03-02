<?php

namespace Detail\FileConversion\Processing\Adapter\Blitline\Func\Script;

use Detail\FileConversion\Processing\Exception;

class ValueOption extends BaseOption
{
    const NAME = 'value';

    /**
     * @var string
     */
    protected $value;

    /**
     * @param string $name
     * @param string $argument
     * @param string $value
     */
    public function __construct($name, $argument, $value = null)
    {
        parent::__construct($name, $argument);

        if ($value !== null) {
            $this->setValue($value);
        }
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = trim($value);
    }

    /**
     * @return string
     */
    public function toString()
    {
        $value = $this->getValue();

        if ($value === null || $value === '') {
            throw new Exception\RuntimeException(
                sprintf(
                    'A value for option %s is required',
                    $this->getName() ? ('"' . $this->getName() . '"') : '{without-name}'
                )
            );
        }

        return '-' . $this->getArgument() . ' ' . escapeshellarg($value);
    }
}

<?php

namespace Detail\FileConversion\Client\Job\Definition;

interface DefinitionInterface
{
    /**
     * @param array $options
     * @return DefinitionInterface
     */
    public function applyOptions(array $options);

    /**
     * @return array
     */
    public function toArray();
}

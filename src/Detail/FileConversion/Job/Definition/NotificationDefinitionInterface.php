<?php

namespace Detail\FileConversion\Job\Definition;

interface NotificationDefinitionInterface extends DefinitionInterface
{
    /**
     * @param string $type
     * @return NotificationDefinitionInterface
     */
    public function setType($type);

    /**
     * @return string
     */
    public function getType();

    /**
     * @param array $params
     * @return NotificationDefinitionInterface
     */
    public function setParams(array $params);

    /**
     * @return array
     */
    public function getParams();
}

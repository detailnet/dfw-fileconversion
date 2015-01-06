<?php

namespace Detail\FileConversion\Job\Definition;

interface ActionDefinitionInterface extends DefinitionInterface
{
    /**
     * @param string $name
     * @return ActionDefinitionInterface
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param array $params
     * @return ActionDefinitionInterface
     */
    public function setParams(array $params);

    /**
     * @return array
     */
    public function getParams();

    /**
     * @param array $saveOptions
     * @return ActionDefinitionInterface
     */
    public function setSaveOptions(array $saveOptions);

    /**
     * @return array
     */
    public function getSaveOptions();
}

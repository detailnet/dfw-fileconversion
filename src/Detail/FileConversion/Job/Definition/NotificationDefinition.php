<?php

namespace Detail\FileConversion\Job\Definition;

class NotificationDefinition extends BaseDefinition implements
    NotificationDefinitionInterface
{
    const OPTION_TYPE   = 'type';
    const OPTION_PARAMS = 'params';

    /**
     * @inheritdoc
     */
    public function setType($name)
    {
        $this->setOption(self::OPTION_TYPE, $name);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getType()
    {
        return $this->getOption(self::OPTION_TYPE);
    }

    /**
     * @inheritdoc
     */
    public function setParams(array $params)
    {
        $this->setOption(self::OPTION_PARAMS, $params);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getParams()
    {
        return $this->getOption(self::OPTION_PARAMS);
    }
}

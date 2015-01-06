<?php

namespace Detail\FileConversion\Job\Definition;

class JobDefinition extends BaseDefinition implements
    JobDefinitionInterface
{
    const OPTION_SOURCE_URL       = 'source_url';
//    const OPTION_POSTBACK_URL = 'postback_url';
    const OPTION_ACTIONS    = 'actions';

    protected $options = array(
        self::OPTION_ACTIONS => array(),
    );

    /**
     * @inheritdoc
     */
    public function setSourceUrl($url)
    {
        $this->setOption(self::OPTION_SOURCE_URL, $url);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getSourceUrl()
    {
        return $this->getOption(self::OPTION_SOURCE_URL);
    }

//    /**
//     * @param string $url
//     * @return JobDefinitionInterface
//     */
//    public function setPostbackUrl($url)
//    {
//        $this->setOption(self::OPTION_POSTBACK_URL, $url);
//        return $this;
//    }
//
//    /**
//     * @return string
//     */
//    public function getPostbackUrl()
//    {
//        return $this->getOption(self::OPTION_POSTBACK_URL);
//    }

    /**
     * @inheritdoc
     */
    public function setActions(array $actions)
    {
        /** @todo Check that array contains valid actions */
        $this->setOption(self::OPTION_ACTIONS, $actions);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getActions()
    {
        return $this->getOption(self::OPTION_ACTIONS);
    }

    /**
     * @inheritdoc
     */
    public function addAction($action)
    {
        /** @todo Check that is array or ActionDefinition object */
        $this->setActions(array($action)); // Will get merged with existing actions
        return $this;
    }
}

<?php

namespace Detail\FileConversion\Client\Job\Definition;

class JobDefinition extends BaseDefinition implements
    JobDefinitionInterface
{
    const OPTION_SOURCE_URL    = 'source_url';
    const OPTION_ACTIONS       = 'actions';
    const OPTION_NOTIFICATIONS = 'notifications';

    /**
     * @var array
     */
    protected $options = array(
        self::OPTION_ACTIONS => array(),
        self::OPTION_NOTIFICATIONS => array(),
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

    /**
     * @inheritdoc
     */
    public function setNotifications(array $notifications)
    {
        /** @todo Check that array contains valid notifications */
        $this->setOption(self::OPTION_NOTIFICATIONS, $notifications);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getNotifications()
    {
        return $this->getOption(self::OPTION_NOTIFICATIONS);
    }

    /**
     * @inheritdoc
     */
    public function addNotification($notification)
    {
        /** @todo Check that is array or NotificationDefinition object */
        $this->setNotifications(array($notification)); // Will get merged with existing notifications
        return $this;
    }
}

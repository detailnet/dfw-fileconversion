<?php

namespace Detail\FileConversion\Client\Job\Definition;

interface JobDefinitionInterface extends DefinitionInterface
{
    /**
     * @param string $url
     * @return JobDefinitionInterface
     */
    public function setSourceUrl($url);

    /**
     * @return string
     */
    public function getSourceUrl();

    /**
     * @param array|ActionDefinitionInterface[] $actions
     * @return JobDefinitionInterface
     */
    public function setActions(array $actions);

    /**
     * @return array|ActionDefinitionInterface[]
     */
    public function getActions();

    /**
     * @param array|ActionDefinitionInterface $actions
     * @return JobDefinitionInterface
     */
    public function addAction($actions);

    /**
     * @param array|NotificationDefinitionInterface[] $notifications
     * @return JobDefinitionInterface
     */
    public function setNotifications(array $notifications);

    /**
     * @return array|NotificationDefinitionInterface[]
     */
    public function getNotifications();

    /**
     * @param array|NotificationDefinitionInterface $notification
     * @return JobDefinitionInterface
     */
    public function addNotification($notification);
}

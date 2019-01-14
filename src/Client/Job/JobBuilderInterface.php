<?php

namespace Detail\FileConversion\Client\Job;

interface JobBuilderInterface
{
    /**
     * @return Definition\JobDefinitionInterface
     */
    public function createJob();

    /**
     * @return Definition\ActionDefinitionInterface
     */
    public function createAction();

    /**
     * @return Definition\NotificationDefinitionInterface
     */
    public function createNotification();
}

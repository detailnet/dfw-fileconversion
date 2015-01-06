<?php

namespace Detail\FileConversion\Job;

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
}

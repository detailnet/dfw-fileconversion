<?php

namespace Detail\FileConversion\Job\Definition;

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

//    /**
//     * @param string $url
//     * @return JobDefinitionInterface
//     */
//    public function setPostbackUrl($url);
//
//    /**
//     * @return string
//     */
//    public function getPostbackUrl();

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
}

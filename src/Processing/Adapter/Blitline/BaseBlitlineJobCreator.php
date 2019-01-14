<?php

namespace Detail\FileConversion\Processing\Adapter\Blitline;

use Detail\FileConversion\Processing\Adapter;
use Detail\FileConversion\Processing\Task;

abstract class BaseBlitlineJobCreator extends Adapter\BaseJobCreator implements
    BlitlineJobCreatorInterface
{
    /**
     * @var FunctionProviderInterface
     */
    protected $functions;

    /**
     * @param FunctionProviderInterface $functions
     * @param array $options
     */
    public function __construct(FunctionProviderInterface $functions, array $options = [])
    {
        parent::__construct($options);

        $this->setFunctions($functions);
    }

    /**
     * @return FunctionProviderInterface
     */
    public function getFunctions()
    {
        return $this->functions;
    }

    /**
     * @param FunctionProviderInterface $functions
     */
    public function setFunctions(FunctionProviderInterface $functions)
    {
        $this->functions = $functions;
    }

    /**
     * Extract task's actions.
     *
     * @param Task\TaskInterface $task
     * @return string[]
     */
    public function getActions(Task\TaskInterface $task)
    {
        return array_keys($this->getActionParams($task));
    }

    /**
     * @param string $action
     * @param array $params
     * @return Func\FunctionInterface
     */
    protected function createFunction($action, array $params = [])
    {
        $function = $this->getFunctions()->getFunction($action);

        // Update the default option values for pre-configured functions
        $function->applyParams($params);

        return $function;
    }
}

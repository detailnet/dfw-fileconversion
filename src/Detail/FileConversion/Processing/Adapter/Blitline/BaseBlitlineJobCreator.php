<?php

namespace Detail\FileConversion\Processing\Adapter\Blitline;

use Detail\FileConversion\Processing\Action;
use Detail\FileConversion\Processing\Adapter;
use Detail\FileConversion\Processing\Exception;
use Detail\FileConversion\Processing\Task;

abstract class BaseBlitlineJobCreator extends Adapter\BaseJobCreator implements
    BlitlineJobCreatorInterface
{
    /**
     * @var array
     */
    protected $functions;

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
     * @return string
     */
    protected function createFunction($action, array $params = array())
    {
        // Just create a standard function by the same name as the action
        if (!isset($this->functions[$action])) {
            return $this->createStandardFunction($action, $params);
        }

        /** @var Func\FunctionInterface $function */
        $function = $this->functions[$action];

        // Update the default option values for pre-configured functions
        $function->applyParams($params);

        return $function;
    }

    /**
     * @param string $action
     * @param array $params
     * @return string
     */
    private function createStandardFunction($action, array $params = array())
    {
        return new Func\StandardFunction($action, $params);
    }
}

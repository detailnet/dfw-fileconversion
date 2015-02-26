<?php

namespace Detail\FileConversion\Processing\Adapter\Blitline;

use Detail\FileConversion\Processing\Action;
use Detail\FileConversion\Processing\Adapter;
use Detail\FileConversion\Processing\Exception;
use Detail\FileConversion\Processing\Task;

abstract class BaseBlitlineJobCreator extends Adapter\BaseJobCreator implements
    BlitlineJobCreatorInterface
{
    const FUNCTION_RESIZE_TO_FIT = 'resize_to_fit';
    const FUNCTION_SCRIPT        = 'script';

    /**
     * @var string[]
     */
    protected static $actionToFunctionMapping = array(
        Action\ThumbnailAction::NAME => self::FUNCTION_RESIZE_TO_FIT, /** @todo Replace with script */
    );

//    protected $actionToFunctionConfig = array(
//        Action\ThumbnailAction::NAME => array(
//            'function' => self::FUNCTION_SCRIPT,
//            'options' => array(
//                'logentries_token' => '123',
//            ),
//        ),
//    );

    /**
     * @return string[]
     */
    public static function getActionToFunctionMapping()
    {
        return static::$actionToFunctionMapping;
    }

    /**
     * @param string[] $actionToFunctionMapping
     */
    public static function setActionToFunctionMapping(array $actionToFunctionMapping)
    {
        static::$actionToFunctionMapping = $actionToFunctionMapping;
    }

    /**
     * @param string $action
     * @return string
     */
    protected function getFunction($action)
    {
        $mapping = self::getActionToFunctionMapping();

        if (!isset($mapping[$action])) {
            return $action; // Pass through unknown action as function
        }

        return $mapping[$action];
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
}

<?php

namespace  Detail\FileConversion\Response;

use Detail\FileConversion\Exception\RuntimeException;

use Guzzle\Service\Command\OperationCommand;
use Guzzle\Service\Command\ResponseClassInterface as GuzzleResponseInterface;

abstract class BaseResponse implements
    ResponseInterface,
    GuzzleResponseInterface
{
    /**
     * @var array
     */
    protected $result = array();

    /**
     * @param OperationCommand $command
     * @return ResponseInterface
     */
    public static function fromCommand(OperationCommand $command)
    {
        $result = $command->getResponse()->json();

        return new static($result);
    }

    /**
     * @param array $result
     */
    public function __construct(array $result)
    {
        $this->result = $result;
    }

    /**
     * @param string $key
     * @return array|mixed
     */
    public function getResult($key = null)
    {
        $result = $this->result;

        if ($key !== null) {
            if (!isset($result[$key])) {
                throw new RuntimeException(sprintf('Result does not contain "%s"', $key));
            }

            $result = $result[$key];
        }

        return $result;
    }

    /**
     * @return boolean
     */
    public function isSuccess()
    {
        return !$this->isError();
    }

    /**
     * @return boolean
     */
    public function isError()
    {
        return $this->getError() !== null;
    }

    /**
     * @return string|null
     */
    public function getError()
    {
        $result = $this->getResult();

        return isset($result['error']) ? $result['error'] : null;
    }
}

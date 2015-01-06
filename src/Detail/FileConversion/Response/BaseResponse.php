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

    /**
     * @param string $key
     * @param array $factory
     * @param bool $asPlainResult
     * @return array|mixed
     */
    protected function getSubResults($key, $factory, $asPlainResult = false)
    {
        if ($asPlainResult === true) {
            return $this->getResult($key);
        }

        if ($this->$key === null) {
            $results = $this->getResult($key);

            $this->$key = array();

            foreach ($results as $result) {
                $response = $this->getSubResponse($factory, $result);

                array_push($this->$key, $response);
            }
        }

        return $this->$key;
    }

    /**
     * @param string $key
     * @param array $factory
     * @param bool $asPlainResult
     * @return array|mixed
     */
    protected function getSubResult($key, $factory, $asPlainResult = false)
    {
        if ($asPlainResult === true) {
            return $this->getResult($key);
        }

        if ($this->$key === null) {
            $result = $this->getResult($key);
            $response = $this->getSubResponse($factory, $result);

            $this->$key = $response;
        }

        return $this->$key;
    }

    /**
     * @param array $factory
     * @param array $result
     * @return ResponseInterface
     */
    private function getSubResponse($factory, $result)
    {
        /** @todo Check if factory is callable */

        $response = call_user_func($factory, $result);

        /** @todo Check if response is an ResponseInterface object */

        return $response;
    }
}

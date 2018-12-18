<?php

namespace Detail\FileConversion\Client\Response;

use DateTime;

use GuzzleHttp\Command\Guzzle\Operation;
use GuzzleHttp\Psr7\Response as PsrResponse;

use JmesPath\Env as JmesPath;

use Detail\FileConversion\Client\Exception;

abstract class BaseResponse implements
    ResponseInterface,
    \ArrayAccess
{
    /**
     * @var PsrResponse
     */
    protected $httpResponse;

    /**
     * @var array
     */
    protected $result;

    /**
     * @param array $result
     * @return static
     */
    public static function fromResult(array $result)
    {
        $response = new PsrResponse(200, [], json_encode($result));

        return new static($response);
    }

    /**
     * @param Operation $operation
     * @param PsrResponse $response
     * @return BaseResponse
     */
    public static function fromOperation(Operation $operation, PsrResponse $response): ResponseInterface
    {
        return new static($response);
    }

    /**
     * @param PsrResponse $response
     */
    public function __construct(PsrResponse $response)
    {
        $this->httpResponse = $response;
    }

    /**
     * @return PsrResponse
     */
    public function getHttpResponse()
    {
        return $this->httpResponse;
    }

    /**
     * @param string $expression
     * @param boolean $failOnNull
     * @return array|mixed|null
     */
    public function getResult($expression = null, $failOnNull = true)
    {
        if ($this->result === null) {
            $this->result = $this->extractData();
        }

        $result = $this->result;

        if ($expression !== null) {
            $result = $this->search($expression, $failOnNull);
        }

        return $result;
    }

    /**
     * @param string $expression
     * @param boolean $failOnNull
     * @return DateTime|null
     */
    public function getDateResult($expression, $failOnNull = true)
    {
        $date = $this->getResult($expression, $failOnNull);

        return ($date !== null) ? new DateTime($date) : null;
    }

    /**
     * @param string|integer $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        $data = $this->getResult();

        if (isset($data[$offset])) {
            return $data[$offset];
        }

        return null;
    }

    /**
     * @param string|integer $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        throw new Exception\RuntimeException('Data cannot be set');
    }

    /**
     * @param string|integer $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->getResult()[$offset]);
    }

    /**
     * @param string|integer $offset
     */
    public function offsetUnset($offset)
    {
        throw new Exception\RuntimeException('Data cannot be unset');
    }

    /**
     * @param string $expression
     * @param boolean $failOnNull
     * @return mixed|null
     */
    protected function search($expression, $failOnNull = false)
    {
        $result = JmesPath::search($expression, $this->getResult());

        if ($result === null && $failOnNull === true) {
            throw new Exception\RuntimeException(sprintf('Result does not contain "%s"', $expression));
        }

        return $result;
    }

    protected function extractData(): array
    {
        try {
            $data = $this->decodeJson($this->getHttpResponse()->getBody());

            return is_array($data) ? $data : [];
        } catch (\Exception $e) {
            throw new Exception\RuntimeException(
                sprintf('Failed to extract data from HTTP response: %s', $e->getMessage()),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * @param string $key
     * @param array $factory
     * @param boolean $asPlainResult
     * @param boolean $failOnNull
     * @return array|mixed
     */
    protected function getSubResults($key, $factory, $asPlainResult = false, $failOnNull = true)
    {
        $results = $this->getResult($key, $failOnNull);

        if ($asPlainResult === true) {
            return $results;
        }

        if ($this->$key === null) {
            $this->$key = [];

            if (is_array($results)) {
                foreach ($results as $result) {
                    $response = $this->getSubResponse($factory, $result);

                    array_push($this->$key, $response);
                }
            }
        }

        return $this->$key;
    }

    /**
     * @param string $key
     * @param array $factory
     * @param boolean $asPlainResult
     * @param boolean $failOnNull
     * @return array|mixed
     */
    protected function getSubResult($key, $factory, $asPlainResult = false, $failOnNull = true)
    {
        $result = $this->getResult($key, $failOnNull);

        if ($asPlainResult === true) {
            return $result;
        }

        if ($this->$key === null && $result !== null) {
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

    private function decodeJson(?string $value): array
    {
        $data = json_decode($value, true);

        if (!$data) {
            $message = 'Unknown error';
            $jsonError = json_last_error();

            if ($jsonError !== JSON_ERROR_NONE) {
                $jsonMessage = json_last_error_msg();

                if ($jsonMessage !== false) {
                    $message = $jsonMessage;
                }
            }

            throw new Exception\RuntimeException(
                sprintf('Unable to decode JSON: %s', $message)
            );
        } elseif (!is_array($data)) {
            throw new Exception\RuntimeException(
                sprintf('Invalid JSON: Expected array but got %s', gettype($data))
            );
        }

        return $data;
    }
}

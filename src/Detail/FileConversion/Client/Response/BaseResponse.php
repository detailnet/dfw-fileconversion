<?php

namespace Detail\FileConversion\Client\Response;

use DateTime;

use GuzzleHttp\Exception\ParseException;
use GuzzleHttp\Message\Response as HttpResponse;
use GuzzleHttp\Message\ResponseInterface as HttpResponseInterface;
use GuzzleHttp\Stream\Stream;

use JmesPath\Env as JmesPath;

use Detail\FileConversion\Client\Exception;

abstract class BaseResponse implements
    ResponseInterface,
    \ArrayAccess
{
    /**
     * @var HttpResponseInterface
     */
    protected $httpResponse;

    /**
     * @var array
     */
    protected $result;

    /**
     * @param HttpResponseInterface $response
     * @return static
     */
    public static function fromHttpResponse(HttpResponseInterface $response)
    {
        return new static($response);
    }

    /**
     * @param array $result
     * @return static
     */
    public static function fromResult(array $result)
    {
        $response = new HttpResponse(200, array(), Stream::factory(json_encode($result)));

        return static::fromHttpResponse($response);
    }

    /**
     * @param HttpResponseInterface $response
     */
    public function __construct(HttpResponseInterface $response)
    {
        $this->httpResponse = $response;
    }

    /**
     * @return HttpResponseInterface
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
            try {
                $this->result = $this->getHttpResponse()->json() ?: array();
            } catch (ParseException $e) {
                // Handle as server exception because it was the server that produces invalid JSON...
                throw new Exception\ServerException($e->getMessage(), $e);
            }
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
        throw new Exception\DomainException('Data cannot be set');
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
        throw new Exception\DomainException('Data cannot be unset');
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
            $this->$key = array();

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
}

<?php

namespace Application\Job\Application\JobProcessing\Adapter\Blitline;

use Application\Job\Domain\Exception\RuntimeException;

class BlitlineResponse
{
    /**
     * @var array
     */
    protected $result = array();

    /**
     * @param array $result
     * @return BlitlineResponse
     */
    public static function fromArray(array $result)
    {
        return new self($result);
    }

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
     * @return string
     */
    public function getJobId()
    {
        return $this->getResult('job_id');
    }

    /**
     * @return array
     */
    public function getImages()
    {
        return $this->getResult('images');
    }

    /**
     * @return array
     */
    public function getOriginalMeta()
    {
        return $this->getResult('original_meta');
    }
}

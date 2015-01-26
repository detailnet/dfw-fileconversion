<?php

namespace Application\Job\Application\JobProcessing\Task;

class Output //implements
//    OutputInterface
{
    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var array
     */
    protected $meta = array();

    /**
     * @param string $identifier
     * @param string $url
     * @param array $meta
     */
    public function __construct($identifier, $url, array $meta)
    {
        $this->identifier = $identifier;
        $this->url = $url;
        $this->meta = $meta;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return array
     */
    public function getMeta()
    {
        return $this->meta;
    }
}

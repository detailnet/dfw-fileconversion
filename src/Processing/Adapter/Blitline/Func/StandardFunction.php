<?php

namespace Detail\FileConversion\Processing\Adapter\Blitline\Func;

class StandardFunction extends BaseFunction
{
    /**
     * @var array
     */
    protected $params;

    /**
     * @param string $name
     * @param array $options
     * @return self
     */
    public static function fromOptions($name, array $options)
    {
        return new static($name, $options);
    }

    /**
     * @param string $name
     * @param array $params
     */
    public function __construct($name, array $params = [])
    {
        $this->setName($name);
        $this->setParams($params);
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * @param array $params
     * @return array
     */
    public function applyParams(array $params)
    {
        $params = array_merge($this->getParams(), $params);

        $this->setParams($params);

        return $params;
    }
}

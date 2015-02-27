<?php

namespace Detail\FileConversion\Processing\Adapter\Blitline\Func;

class ScriptFunction extends BaseFunction
{
    /**
     * @var string
     */
    protected $name = 'script';

    /**
     * @var string
     */
    protected $executable;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var array
     */
    protected $files;

    /**
     * @param string $executable
     * @param array $options
     * @param array $files
     */
    public function __construct($executable, array $options = array(), array $files = array())
    {
        $this->setExecutable($executable);
        $this->setOptions($options);
        $this->setFiles($files);
    }

    /**
     * @return string
     */
    public function getExecutable()
    {
        return $this->executable;
    }

    /**
     * @param string $executable
     */
    public function setExecutable($executable)
    {
        $this->executable = $executable;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param array $files
     */
    public function setFiles(array $files)
    {
        $this->files = $files;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        $executable = $this->getExecutable();

        foreach ($this->getOptions() as $option) {
            $executable .= ' ' . $option;
        }

        $params = array(
            'files' => $this->getFiles(),
            'executable' => $executable,
        );

        return $params;
    }

    /**
     * @param array $params
     * @return array
     */
    public function applyParams(array $params)
    {
        foreach ($params as $key => $value) {
            $option = $this->getOption($key);

            // Ignore:
            // - Params for un-configured options
            // - Params for no-value options (doesn't make sense to provide a value)
            if (!$option instanceof Script\ValueOption) {
                continue;
            }

            $option->setValue($value);
        }

        return $this->getOptionParams();
    }

    /**
     * @return array
     */
    public function getOptionParams()
    {
        $params = array();

        foreach ($this->getOptions() as $option) {
            if (!$option instanceof Script\ValueOption) {
                continue; // Ignore plain-string and no-value options as they provide no (real) params
            }

            /** @todo Sometimes, not all values of the script's options should be changeable */
            $params[$option->getName()] = $option->getValue();
        }

        return $params;
    }

    /**
     * @param string $name
     * @return Script\OptionInterface|null
     */
    protected function getOption($name)
    {
        return isset($this->options[$name]) ? $this->options[$name] : null;
    }
}

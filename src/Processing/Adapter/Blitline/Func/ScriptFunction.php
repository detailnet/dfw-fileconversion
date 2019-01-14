<?php

namespace Detail\FileConversion\Processing\Adapter\Blitline\Func;

use Detail\FileConversion\Processing\Exception;

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
     * @var Script\OptionInterface[]
     */
    protected $options;

    /**
     * @var string[]
     */
    protected $files;

    /**
     * @param string $name
     * @param array $options
     * @return self
     */
    public static function fromOptions($name, array $options)
    {
        if (!isset($options['executable'])) {
            throw new Exception\InvalidArgumentException('Missing required option "executable"');
        }

        $executableOptions = [];
        $files = [];

        if (isset($options['executable_options'])) {
            if (!is_array($options['executable_options'])) {
                throw new Exception\InvalidArgumentException(
                    'Invalid option "executable_options"; value must be an array'
                );
            }

            $executableOptions = self::getExecutableOptions($options['executable_options']);
        }

        if (isset($options['files'])) {
            if (!is_array($options['files'])) {
                throw new Exception\InvalidArgumentException(
                    'Invalid option "files"; value must be an array'
                );
            }

            $files = $options['files'];
        }

        return new static($options['executable'], $executableOptions, $files);
    }

    /**
     * @param string $executable
     * @param Script\OptionInterface[] $options
     * @param array $files
     */
    public function __construct($executable, array $options = [], array $files = [])
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
     * @return Script\OptionInterface[]
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param Script\OptionInterface[] $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * @return string[]
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param string[] $files
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
            if ($option->isEnabled()) {
                $executable .= ' ' . $option->toString();
            }
        }

        $params = [
            'files' => implode(',', $this->getFiles()),
            'executable' => $executable,
        ];

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

            // A PlainOption can be enabled/disabled trough a boolean value
            if ($option instanceof Script\PlainOption) { // $option !== null is checked here too
                $option->setEnabled($value !== false);
            }

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
        $params = [];

        foreach ($this->getOptions() as $option) {
            if (!$option instanceof Script\ValueOption) {
                continue; // Ignore no-value options as they provide no (real) params
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

    /**
     * @param array $optionsConfig
     * @return Script\OptionInterface[]
     */
    private static function getExecutableOptions(array $optionsConfig)
    {
        $executableOptions = [];

        foreach ($optionsConfig as $name => $optionConfig) {
            if (!isset($optionConfig['type'])) {
                throw new Exception\InvalidArgumentException(
                    sprintf(
                        'Executable option "%s": Missing type',
                        $name
                    )
                );
            }

            if (!isset($optionConfig['argument'])) {
                throw new Exception\InvalidArgumentException(
                    sprintf(
                        'Executable option "%s": Missing argument',
                        $name
                    )
                );
            }

            switch ($optionConfig['type']) {
                case Script\ValueOption::NAME:
                    $option = new Script\ValueOption(
                        $name,
                        $optionConfig['argument'],
                        isset($optionConfig['value']) ? $optionConfig['value'] : null,
                        isset($optionConfig['enabled']) ? $optionConfig['enabled'] : true
                    );
                    break;
                case Script\PlainOption::NAME:
                default:
                    $option = new Script\PlainOption(
                        $name,
                        $optionConfig['argument'],
                        isset($optionConfig['enabled']) ? $optionConfig['enabled'] : true
                    );
                    break;
            }

            $executableOptions[$name] = $option;
        }

        return $executableOptions;
    }
}

<?php

namespace DetailTest\FileConversion\Client\Job\Definition;

use RuntimeException;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

abstract class DefinitionTestCase extends TestCase
{
    /**
     * @var MockObject
     */
    protected $definition;

    /**
     * @return string
     */
    abstract protected function getDefinitionClass();

    protected function setUp()
    {
        $definitionClass = $this->getDefinitionClass();

        if (!class_exists($definitionClass)) {
            throw new RuntimeException(
                sprintf('Definition class "%s" does not exist', $definitionClass)
            );
        }

        $this->definition = $this->getMockBuilder($definitionClass)
            ->setMethods(['setOption', 'getOption'])
            ->getMock();

        $this->setMethodReturnValue($this->definition, 'setOption', $this->definition);
    }

    /**
     * @return MockObject
     */
    protected function getDefinition()
    {
        return $this->definition;
    }

    /**
     * @param MockObject $definition
     * @param string $method
     * @param mixed $returnValue
     */
    protected function setMethodReturnValue(MockObject $definition, $method, $returnValue = null)
    {
        $definition
            ->expects($this->any())
            ->method($method)
            ->will($this->returnValue($returnValue));
    }
}

<?php

namespace DetailTest\FileConversion\Job;

use PHPUnit_Framework_TestCase as TestCase;

use Detail\FileConversion\Job\JobBuilder;

class JobBuilderTest extends TestCase
{
    /**
     * @var JobBuilder
     */
    protected $jobBuilder;

    public function provideJobDefinitionDefaultOptions()
    {
        return array(
            array(
                array(
                ),
                array(
                ),
            ),
            array(
                array(
                    'job.src' => 'job.src',
                    'function.name' => 'function.name'
                ),
                array(
                    'src' => 'job.src',
                ),
            ),
            array(
                array(
                    'src' => 'src',
                    'job' => 'job',
                    'function' => 'function',
                    'job.' => 'job.',
                    'function.' => 'function.',
                ),
                array(
                ),
            ),
        );
    }

    public function provideActionDefinitionDefaultOptions()
    {
        return array(
            array(
                array(
                ),
                array(
                ),
            ),
            array(
                array(
                    'job.source_url' => 'job.source_url',
                    'action.name' => 'action.name'
                ),
                array(
                    'name' => 'action.name',
                ),
            ),
            array(
                array(
                    'source_url' => 'source_url',
                    'job' => 'job',
                    'action' => 'action',
                    'job.' => 'job.',
                    'action.' => 'action.',
                ),
                array(
                ),
            ),
        );
    }

    protected function setUp()
    {
        $this->jobBuilder = new JobBuilder();
    }

    public function testJobClassCanBeSet()
    {
        $this->assertEquals(
            'Detail\FileConversion\Job\Definition\JobDefinition',
            $this->jobBuilder->getJobClass()
        );

        $class = 'CustomJobDefinitionClass';

        $this->jobBuilder->setJobClass($class);

        $this->assertEquals($class, $this->jobBuilder->getJobClass());
    }

    public function testActionClassCanBeSet()
    {
        $this->assertEquals(
            'Detail\FileConversion\Job\Definition\ActionDefinition',
            $this->jobBuilder->getActionClass()
        );

        $class = 'CustomActionDefinitionClass';

        $this->jobBuilder->setActionClass($class);

        $this->assertEquals($class, $this->jobBuilder->getActionClass());
    }

    public function testDefaultOptionCanBeSet()
    {
        $key          = 'key';
        $value        = 'value';
        $defaultValue = 'defaultValue';

        $this->assertNull($this->jobBuilder->getDefaultOption($key));

        $this->jobBuilder->setDefaultOption($key, $value);

        $this->assertEquals($value, $this->jobBuilder->getDefaultOption($key));
        $this->assertEquals(
            $defaultValue,
            $this->jobBuilder->getDefaultOption('non-existing-key', $defaultValue)
        );
    }

    public function testDefaultOptionsCanBeSet()
    {
        $options = array('key' => 'value');

        $this->jobBuilder->setDefaultOptions($options);

        $this->assertEquals($options, $this->jobBuilder->getDefaultOptions());
    }

    public function testCanCreateJobDefinition()
    {
        $job = $this->jobBuilder->createJob();

        $this->assertInstanceOf('Detail\FileConversion\Job\Definition\JobDefinition', $job);
    }

    public function testCanCreateActionDefinition()
    {
        $job = $this->jobBuilder->createAction();

        $this->assertInstanceOf('Detail\FileConversion\Job\Definition\ActionDefinition', $job);
    }

    public function testDefinitionCreationWithMissingClassThrowsException()
    {
        $this->setExpectedException('Detail\FileConversion\Exception\RuntimeException');

        $this->jobBuilder->setJobClass('NonExistingDefinitionClass');
        $this->jobBuilder->createJob();
    }

    public function testDefinitionCreationWithInvalidInterfaceThrowsException()
    {
        $this->setExpectedException('Detail\FileConversion\Exception\RuntimeException');

        // Using existing class which doesn't implement Detail\FileConversion\Job\JobBuilder\Definition\DefinitionInterface
        $this->jobBuilder->setJobClass('Detail\FileConversion\Job\JobBuilder');
        $this->jobBuilder->createJob();
    }

    /**
     * @param array $options
     * @param array $expectedOptions
     * @dataProvider provideJobDefinitionDefaultOptions
     */
    public function testDefaultOptionsCanBeGetForJobDefinition(array $options, array $expectedOptions)
    {
        $this->jobBuilder->setDefaultOptions($options);

        $jobDefinition = $this->jobBuilder->createJob();

        $this->assertEquals($expectedOptions, $this->jobBuilder->getDefaultOptions($jobDefinition));
    }

    /**
     * @param array $options
     * @param array $expectedOptions
     * @dataProvider provideActionDefinitionDefaultOptions
     */
    public function testDefaultOptionsCanBeGetForFunctionDefinition(array $options, array $expectedOptions)
    {
        $this->jobBuilder->setDefaultOptions($options);

        $actionDefinition = $this->jobBuilder->createAction();

        $this->assertEquals($expectedOptions, $this->jobBuilder->getDefaultOptions($actionDefinition));
    }

    public function testDefaultOptionsAreEmptyUnknownDefinition()
    {
        $this->jobBuilder->setDefaultOptions(
            array('job.src' => 'job.src', 'function.name' => 'function.name')
        );

        $definition = $this->getMock('Detail\FileConversion\Job\Definition\DefinitionInterface');

        /** @var \Detail\FileConversion\Job\Definition\DefinitionInterface $definition */

        $this->assertEquals(array(), $this->jobBuilder->getDefaultOptions($definition));
    }
}

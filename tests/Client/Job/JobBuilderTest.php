<?php

namespace DetailTest\FileConversion\Client\Job;

use PHPUnit\Framework\TestCase;

use Detail\FileConversion\Client\Exception as ClientException;
use Detail\FileConversion\Client\Job\Definition;
use Detail\FileConversion\Client\Job\JobBuilder;

class JobBuilderTest extends TestCase
{
    /**
     * @var JobBuilder
     */
    protected $jobBuilder;

    public function provideJobDefinitionDefaultOptions()
    {
        return [
            [
                [
                ],
                [
                ],
            ],
            [
                [
                    'job.src' => 'job.src',
                    'function.name' => 'function.name'
                ],
                [
                    'src' => 'job.src',
                ],
            ],
            [
                [
                    'src' => 'src',
                    'job' => 'job',
                    'function' => 'function',
                    'job.' => 'job.',
                    'function.' => 'function.',
                ],
                [
                ],
            ],
        ];
    }

    public function provideActionDefinitionDefaultOptions()
    {
        return [
            [
                [
                ],
                [
                ],
            ],
            [
                [
                    'job.source_url' => 'job.source_url',
                    'action.name' => 'action.name'
                ],
                [
                    'name' => 'action.name',
                ],
            ],
            [
                [
                    'source_url' => 'source_url',
                    'job' => 'job',
                    'action' => 'action',
                    'job.' => 'job.',
                    'action.' => 'action.',
                ],
                [
                ],
            ],
        ];
    }

    protected function setUp()
    {
        $this->jobBuilder = new JobBuilder();
    }

    public function testJobClassCanBeSet()
    {
        $this->assertEquals(
            Definition\JobDefinition::CLASS,
            $this->jobBuilder->getJobClass()
        );

        $class = 'CustomJobDefinitionClass';

        $this->jobBuilder->setJobClass($class);

        $this->assertEquals($class, $this->jobBuilder->getJobClass());
    }

    public function testActionClassCanBeSet()
    {
        $this->assertEquals(
            Definition\ActionDefinition::CLASS,
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
        $options = ['key' => 'value'];

        $this->jobBuilder->setDefaultOptions($options);

        $this->assertEquals($options, $this->jobBuilder->getDefaultOptions());
    }

    public function testCanCreateJobDefinition()
    {
        $job = $this->jobBuilder->createJob();

        $this->assertInstanceOf(Definition\JobDefinition::CLASS, $job);
    }

    public function testCanCreateActionDefinition()
    {
        $action = $this->jobBuilder->createAction();

        $this->assertInstanceOf(Definition\ActionDefinition::CLASS, $action);
    }

    public function testCanCreateNotificationDefinition()
    {
        $notification = $this->jobBuilder->createNotification();

        $this->assertInstanceOf(Definition\NotificationDefinition::CLASS, $notification);
        $this->assertEquals('webhook', $notification->getType());
    }

    public function testDefinitionCreationWithMissingClassThrowsException()
    {
        $this->expectException(ClientException\RuntimeException::CLASS);

        $this->jobBuilder->setJobClass('NonExistingDefinitionClass');
        $this->jobBuilder->createJob();
    }

    public function testDefinitionCreationWithInvalidInterfaceThrowsException()
    {
        $this->expectException(ClientException\RuntimeException::CLASS);

        // Using existing class which doesn't implement Detail\FileConversion\Client\Job\JobBuilder\Definition\DefinitionInterface
        $this->jobBuilder->setJobClass(JobBuilder::CLASS);
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
            ['job.src' => 'job.src', 'function.name' => 'function.name']
        );

        $definition = $this->getMockBuilder(Definition\DefinitionInterface::CLASS)->getMock();

        /** @var Definition\DefinitionInterface $definition */

        $this->assertEquals([], $this->jobBuilder->getDefaultOptions($definition));
    }
}

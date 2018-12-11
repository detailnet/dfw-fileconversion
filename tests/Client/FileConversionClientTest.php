<?php

namespace DetailTest\FileConversion\Client\Client;

use PHPUnit_Framework_TestCase as TestCase;

use GuzzleHttp\Command\Command;
use GuzzleHttp\Command\Exception\CommandException;

use Detail\FileConversion\Client\Exception;
use Detail\FileConversion\Client\FileConversionClient;
use Detail\FileConversion\Client\Job\Definition\JobDefinition;
use Detail\FileConversion\Client\Job\JobBuilder;

class FileConversionClientTest extends TestCase
{
//    /** @var FileConversionClient */
//    protected $client;

    public function provideConfigValues()
    {
        return array(
            array('random_application_id'),
        );
    }

//    protected function setUp()
//    {
//        $this->client = new FileConversionClient();
//    }

    /**
     * @param $applicationId
     * @dataProvider provideConfigValues
     */
    public function testFactoryReturnsClient($applicationId)
    {
        $config = array(
            'application_id' => $applicationId
        );

        $jobBuilder = new JobBuilder();

        $client = FileConversionClient::factory($config, $jobBuilder);

        $this->assertInstanceOf('Detail\FileConversion\Client\FileConversionClient', $client);
        $this->assertEquals('https://dws-fileconversion.detailnet.ch/api', $client->getServiceUrl());
        $this->assertEquals($jobBuilder, $client->getJobBuilder());
    }

//    /**
//     * @expectedException \Detail\FileConversion\Client\Exception\InvalidArgumentException
//     */
//    public function testFactoryThrowsExceptionOnMissingConfigurationOptions()
//    {
//        $config = array();
//
//        FileConversionClient::factory($config);
//    }

//    /**
//     * @expectedException \Detail\FileConversion\Client\Exception\InvalidArgumentException
//     */
//    public function testFactoryThrowsExceptionOnBlankConfigurationOptions()
//    {
//        $config = array(
//            'application_id' => '',
//        );
//
//        FileConversionClient::factory($config);
//    }

    /**
     * @param $applicationId
     * @dataProvider provideConfigValues
     */
    public function testClientHasCommands($applicationId)
    {
        $config = array(
            'application_id' => $applicationId
        );

        $client = FileConversionClient::factory($config);

        $this->assertTrue(is_callable(array($client, 'listJobs')));
        $this->assertTrue(is_callable(array($client, 'fetchJob')));
        $this->assertTrue(is_callable(array($client, 'submitJob')));

//        $this->assertInstanceOf('Guzzle\Service\Command\OperationCommand', $client->getCommand('listJobs'));
//        $this->assertEquals(
//            'Detail\FileConversion\Client\Response\JobList',
//            $client->getCommand('listJobs')->getOperation()->getResponseClass()
//        );
//
//        $this->assertInstanceOf('Guzzle\Service\Command\OperationCommand', $client->getCommand('fetchJob'));
//        $this->assertEquals(
//            'Detail\FileConversion\Client\Response\Job',
//            $client->getCommand('fetchJob')->getOperation()->getResponseClass()
//        );
//
//        $this->assertInstanceOf('Guzzle\Service\Command\OperationCommand', $client->getCommand('submitJob'));
//        $this->assertEquals(
//            'Detail\FileConversion\Client\Response\Job',
//            $client->getCommand('submitJob')->getOperation()->getResponseClass()
//        );
    }

    public function testJobBuilderCanBeSet()
    {
        $client = FileConversionClient::factory();

        $this->assertInstanceOf(JobBuilder::CLASS, $client->getJobBuilder());

        $jobBuilder = new JobBuilder();

        $this->assertEquals($client, $client->setJobBuilder($jobBuilder));
        $this->assertEquals($jobBuilder, $client->getJobBuilder());
    }

    public function testCommandExceptionsAreHandled()
    {
//        $commandResponse = array('a' => 'b');
//
        $command = $this->getMockBuilder(Command::CLASS)
            ->disableOriginalConstructor()
            ->getMock();

        $exception = $this->getMockBuilder(CommandException::CLASS)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var CommandException $exception */

        $client = $this->getMockBuilder(FileConversionClient::CLASS)
            ->disableOriginalConstructor()
            ->setMethods(array('getCommand', 'execute'))
            ->getMock();
        $client
            ->expects($this->any())
            ->method('getCommand')
            ->will($this->returnValue($command));
        $client
            ->expects($this->any())
            ->method('execute')
            ->will($this->throwException($exception));

        /** @var FileConversionClient $client */

        $this->setExpectedException(Exception\RuntimeException::CLASS);
        $client->__call('dummyCommand', array());
    }

    public function testCommandsAcceptDefinitions()
    {
        $commandResponse = array('a' => 'b');

        $command = $this->getMockBuilder(Command::CLASS)
            ->disableOriginalConstructor()
            ->getMock();

        $client = $this->getMockBuilder(FileConversionClient::CLASS)
            ->disableOriginalConstructor()
            ->setMethods(array('getCommand', 'execute'))
            ->getMock();
        $client
            ->expects($this->any())
            ->method('getCommand')
            ->will($this->returnValue($command));
        $client
            ->expects($this->any())
            ->method('execute')
            ->will($this->returnValue($commandResponse));

        /** @var FileConversionClient $client */

        $commandArgs = array('c' => 'd');

        $definition = $this->getMock(JobDefinition::CLASS);
        $definition
            ->expects($this->any())
            ->method('toArray')
            ->will($this->returnValue($commandArgs));

        $this->assertEquals($commandResponse, $client->__call('dummyCommand', array($definition)));
    }
}

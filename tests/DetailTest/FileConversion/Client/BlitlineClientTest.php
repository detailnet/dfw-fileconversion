<?php

namespace DetailTest\FileConversion\Client;

use PHPUnit_Framework_TestCase as TestCase;

use Guzzle\Service\Description\ServiceDescription;

use Detail\FileConversion\Client\FileConversionClient;
use Detail\FileConversion\Job\JobBuilder;

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
        $this->assertEquals($config['application_id'], $client->getDefaultOption('query')['application_id']);
        $this->assertEquals('application/json', $client->getDefaultOption('headers')['Accept']);
        $this->assertEquals('https://file-conversion.dws.detailnet.ch/api', $client->getConfig('base_url'));
        $this->assertEquals($jobBuilder, $client->getJobBuilder());
    }

    /**
     * @expectedException \Detail\FileConversion\Exception\InvalidArgumentException
     */
    public function testFactoryThrowsExceptionOnMissingConfigurationOptions()
    {
        $config = array();

        FileConversionClient::factory($config);
    }

    /**
     * @expectedException \Detail\FileConversion\Exception\InvalidArgumentException
     */
    public function testFactoryThrowsExceptionOnBlankConfigurationOptions()
    {
        $config = array(
            'application_id' => '',
        );

        FileConversionClient::factory($config);
    }

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

        $this->assertInstanceOf('Guzzle\Service\Command\OperationCommand', $client->getCommand('fetchJob'));
        $this->assertEquals(
            'Detail\FileConversion\Response\Job',
            $client->getCommand('fetchJob')->getOperation()->getResponseClass()
        );

        $this->assertInstanceOf('Guzzle\Service\Command\OperationCommand', $client->getCommand('createJob'));
        $this->assertEquals(
            'Detail\FileConversion\Response\Job',
            $client->getCommand('createJob')->getOperation()->getResponseClass()
        );
    }

    public function testJobBuilderCanBeSet()
    {
        $client = new FileConversionClient();

        $this->assertInstanceOf('Detail\FileConversion\Job\JobBuilder', $client->getJobBuilder());

        $jobBuilder = new JobBuilder();

        $this->assertEquals($client, $client->setJobBuilder($jobBuilder));
        $this->assertEquals($jobBuilder, $client->getJobBuilder());
    }

    public function testCommandsAcceptDefinitions()
    {
        $commandResponse = array('a' => 'b');

        $command = $this->getMock('Guzzle\Service\Command\OperationCommand');
        $command
            ->expects($this->any())
            ->method('getResult')
            ->will($this->returnValue($commandResponse));

        $client = $this->getMock('Detail\FileConversion\Client\FileConversionClient', array('getCommand'));
        $client
            ->expects($this->any())
            ->method('getCommand')
            ->will($this->returnValue($command));

        /** @var FileConversionClient $client */

        $commandArgs = array('c' => 'd');

        $definition = $this->getMock('Detail\FileConversion\Job\Definition\JobDefinition');
        $definition
            ->expects($this->any())
            ->method('toArray')
            ->will($this->returnValue($commandArgs));

        $this->assertEquals($commandResponse, $client->__call('testCommand', array($definition)));
    }
}

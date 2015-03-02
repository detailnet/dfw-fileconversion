<?php

namespace Detail\FileConversion\Client;

use Guzzle\Common\Collection;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;

//use Detail\FileConversion\Client\Exception\InvalidArgumentException;
use Detail\FileConversion\Client\Subscriber;
use Detail\FileConversion\Client\Job\Definition\DefinitionInterface;
use Detail\FileConversion\Client\Job\JobBuilder;
use Detail\FileConversion\Client\Job\JobBuilderInterface;
use Detail\FileConversion\Client\Response;

/**
 * FileConversion API client.
 *
 * @method Response\JobList listJobs(array $params = array())
 * @method Response\Job fetchJob(array $params = array())
 * @method Response\Job submitJob(mixed $job = array())
 */
class FileConversionClient extends Client
{
    const CLIENT_VERSION = '0.5.0';

    /**
     * @var JobBuilderInterface
     */
    protected $jobBuilder;

    public static function factory($options = array(), JobBuilderInterface $jobBuilder = null)
    {
        $defaultOptions = array(
            'base_url' => 'http://fileconversion.dws.detailnet.ch/api',
            'request.options' => array(
                // Float describing the number of seconds to wait while trying to connect to a server.
                // 0 was the default (wait indefinitely).
                'connect_timeout' => 10,
                // Float describing the timeout of the request in seconds.
                // 0 was the default (wait indefinitely).
                'timeout' => 60, // 60 seconds, may be overridden by individual operations
            ),
        );

//        $requiredOptions = array();
//
//        foreach ($requiredOptions as $optionName) {
//            if (!isset($options[$optionName]) || $options[$optionName] === '') {
//                throw new InvalidArgumentException(
//                    sprintf('Missing required configuration option "%s"', $optionName)
//                );
//            }
//        }

        $config = Collection::fromConfig($options, $defaultOptions);

        $headers = array(
            'Accept' => 'application/json',
        );

        if (isset($options['dws_app_id'])) {
            $headers['DWS-App-ID'] = $options['dws_app_id'];
        }

        if (isset($options['dws_app_key'])) {
            $headers['DWS-App-Key'] = $options['dws_app_key'];
        }

        $client = new self($config->get('base_url'), $config, $jobBuilder);
//        $client->setDefaultOption(
//            'query',
//            array(
//                'application_id' => $config['application_id'],
//            )
//        );
        $client->setDefaultOption('headers', $headers);
        $client->setDescription(
            ServiceDescription::factory(__DIR__ . '/ServiceDescription/FileConversion.php')
        );
        $client->setUserAgent('dfw-fileconversion/' . self::CLIENT_VERSION, true);

        $client->getEventDispatcher()->addSubscriber(new Subscriber\ErrorHandlerSubscriber());
        $client->getEventDispatcher()->addSubscriber(new Subscriber\RequestOptionsSubscriber());

        return $client;
    }

    /**
     * @return JobBuilderInterface
     */
    public function getJobBuilder()
    {
        if ($this->jobBuilder === null) {
            $this->jobBuilder = new JobBuilder();
        }

        return $this->jobBuilder;
    }

    /**
     * @param JobBuilderInterface $jobBuilder
     * @return FileConversionClient
     */
    public function setJobBuilder(JobBuilderInterface $jobBuilder)
    {
        $this->jobBuilder = $jobBuilder;
        return $this;
    }

    /**
     * @return \Guzzle\Http\Message\RequestFactoryInterface
     */
    public function getRequestFactory()
    {
        return $this->requestFactory;
    }

    /**
     * @param string $baseUrl
     * @param array|Collection $config
     * @param JobBuilderInterface $jobBuilder
     */
    public function __construct($baseUrl = '', $config = null, JobBuilderInterface $jobBuilder = null)
    {
        parent::__construct($baseUrl, $config);

        if ($jobBuilder !== null) {
            $this->setJobBuilder($jobBuilder);
        }
    }

    /**
     * @inheritdoc
     */
    public function __call($method, $args)
    {
        if (isset($args[0]) && $args[0] instanceof DefinitionInterface) {
            /** @var DefinitionInterface $definition */
            $definition = $args[0];
            $args[0] = $definition->toArray();
        }

        return parent::__call($method, $args);
    }
}

<?php

namespace Detail\FileConversion\Client;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Command\CommandInterface;
use GuzzleHttp\Command\Guzzle\Description as ServiceDescription;
use GuzzleHttp\Command\Guzzle\GuzzleClient as ServiceClient;

use Detail\FileConversion\Client\Job\Definition\DefinitionInterface;
use Detail\FileConversion\Client\Job\JobBuilder;
use Detail\FileConversion\Client\Job\JobBuilderInterface;
use Detail\FileConversion\Client\Response;

/**
 * FileConversion API client
 *
 * @method Response\JobList listJobs(array $params = [])
 * @method Response\Job fetchJob(array $params = [])
 * @method Response\Job submitJob(mixed $job = [])
 */
class FileConversionClient extends ServiceClient
{
    const CLIENT_VERSION = '1.0.0';

    const OPTION_APP_ID  = 'dws_app_id';
    const OPTION_APP_KEY = 'dws_app_key';

    const HEADER_APP_ID  = 'DWS-App-ID';
    const HEADER_APP_KEY = 'DWS-App-Key';

    /**
     * @var JobBuilderInterface
     */
    private $jobBuilder;

    /**
     * @param array $options
     * @param JobBuilderInterface|null $jobBuilder
     * @return FileConversionClient
     */
    public static function factory($options = [], JobBuilderInterface $jobBuilder = null)
    {
//        $requiredOptions = array(
//            'application_id',
//        );
//
//        foreach ($requiredOptions as $optionName) {
//            if (!isset($options[$optionName]) || $options[$optionName] === '') {
//                throw new Exception\RuntimeException(
//                    sprintf('Missing required configuration option "%s"', $optionName)
//                );
//            }
//        }

        $defaultOptions = [
            'base_uri' => 'https://dws-fileconversion.detailnet.ch/api',
            // Float describing the number of seconds to wait while trying to connect to a server.
            // 0 was the default (wait indefinitely).
            'connect_timeout' => 10,
            // Float describing the timeout of the request in seconds.
            // 0 was the default (wait indefinitely).
            'timeout' => 60, // 60 seconds, may be overridden by individual operations
        ];

        $headers = [
            'Accept' => 'application/json',
            'User-Agent' => 'dfw-fileconversion/' . self::CLIENT_VERSION,
        ];

        if (isset($options[self::OPTION_APP_ID])) {
            $headers[self::HEADER_APP_ID] = $options[self::OPTION_APP_ID];
        }

        if (isset($options[self::OPTION_APP_KEY])) {
            $headers[self::HEADER_APP_KEY] = $options[self::OPTION_APP_KEY];
        }

        // These are always applied
        $overrideOptions = [
            // We're using our own error handling middleware,
            // so disable throwing exceptions on HTTP protocol errors (i.e., 4xx and 5xx responses).
            'http_errors' => false,
            'headers' => $headers,
        ];

        // Apply options
        $config = array_replace_recursive($defaultOptions, $options, $overrideOptions);

        $httpClient = new HttpClient($config);
//        $httpClient->getEmitter()->attach(new Subscriber\Http\ProcessError());

        $description = new ServiceDescription(require __DIR__ . '/ServiceDescription/FileConversion.php');
        $deserializer = new Deserializer($description);
        $client = new static($httpClient, $description, null, $deserializer);

        if ($jobBuilder !== null) {
            $client->setJobBuilder($jobBuilder);
        }

        return $client;
    }

    public function getServiceAppId(): ?string
    {
        return $this->getHeaderOption(self::HEADER_APP_ID);
    }

    public function getServiceAppKey(): ?string
    {
        return $this->getHeaderOption(self::HEADER_APP_KEY);
    }

    public function getServiceUrl(): ?string
    {
        return $this->getHttpClient()->getConfig('base_uri');
    }

    public function getJobBuilder(): JobBuilderInterface
    {
        if ($this->jobBuilder === null) {
            $this->jobBuilder = new JobBuilder();
        }

        return $this->jobBuilder;
    }

    public function setJobBuilder(JobBuilderInterface $jobBuilder)
    {
        $this->jobBuilder = $jobBuilder;
    }

    /**
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public function __call($method, array $args)
    {
        if (isset($args[0]) && $args[0] instanceof DefinitionInterface) {
            /** @var DefinitionInterface $definition */
            $definition = $args[0];
            $args[0] = $definition->toArray();
        }

        return parent::__call($method, $args);
    }

    /**
     * @param string $name
     * @param array $params
     * @return CommandInterface
     */
    public function getCommand($name, array $params = [])
    {
        $command = parent::getCommand($name, $params);
        $requestOptions = $this->getRequestOptions($command);

        if ($requestOptions !== null) {
            $command['@http'] = $requestOptions;
        }

        return $command;
    }

    private function getRequestOptions(CommandInterface $command): ?array
    {
        $operation = $this->getDescription()->getOperation($command->getName());
        $requestOptions = $operation->getData('http');

        return is_array($requestOptions) ? $requestOptions : null;
    }

    private function getHeaderOption(string $option): ?string
    {
        $headers = $this->getHttpClient()->getConfig('headers');

        return array_key_exists($option, $headers) ? $headers[$option] : null;
    }
}

<?php

namespace Detail\FileConversion\Client;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface as HttpClientInterface;
use GuzzleHttp\Command\Guzzle\Description as ServiceDescription;
use GuzzleHttp\Command\Guzzle\DescriptionInterface as ServiceDescriptionInterface;
use GuzzleHttp\Command\Guzzle\GuzzleClient as ServiceClient;

use Detail\FileConversion\Client\Exception;
use Detail\FileConversion\Client\Subscriber;
use Detail\FileConversion\Client\Job\Definition\DefinitionInterface;
use Detail\FileConversion\Client\Job\JobBuilder;
use Detail\FileConversion\Client\Job\JobBuilderInterface;
use Detail\FileConversion\Client\Response;

/**
 * FileConversion API client.
 *
 * @method Response\JobList listJobs(array $params = [])
 * @method Response\Job fetchJob(array $params = [])
 * @method Response\Job submitJob(mixed $job = [])
 */
class FileConversionClient extends ServiceClient
{
    const CLIENT_VERSION = '0.6.0';

    const OPTION_APP_ID  = 'dws_app_id';
    const OPTION_APP_KEY = 'dws_app_key';

    const HEADER_APP_ID  = 'DWS-App-ID';
    const HEADER_APP_KEY = 'DWS-App-Key';

    /**
     * @var JobBuilderInterface
     */
    protected $jobBuilder;

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
//                throw new Exception\InvalidArgumentException(
//                    sprintf('Missing required configuration option "%s"', $optionName)
//                );
//            }
//        }

        $defaultOptions = [
            'base_url' => 'https://dws-fileconversion.detailnet.ch/api',
            'defaults' => [
                // Float describing the number of seconds to wait while trying to connect to a server.
                // 0 was the default (wait indefinitely).
                'connect_timeout' => 10,
                // Float describing the timeout of the request in seconds.
                // 0 was the default (wait indefinitely).
                'timeout' => 60, // 60 seconds, may be overridden by individual operations
            ],
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
            'defaults' => [
                // We're using our own error handler
                // (this disables the use of the internal HttpError subscriber)
                'exceptions' => false,
                'headers' => $headers,
            ],
        ];

        // Apply options
        $config = array_replace_recursive($defaultOptions, $options, $overrideOptions);

        $httpClient = new HttpClient($config);
        $httpClient->getEmitter()->attach(new Subscriber\Http\ProcessError());

        $description = new ServiceDescription(require __DIR__ . '/ServiceDescription/FileConversion.php');
        $client = new static($httpClient, $description, $jobBuilder);

        return $client;
    }

    /**
     * @param HttpClientInterface $client
     * @param ServiceDescriptionInterface $description
     * @param JobBuilderInterface $jobBuilder
     */
    public function __construct(
        HttpClientInterface $client,
        ServiceDescriptionInterface $description,
        JobBuilderInterface $jobBuilder = null
    ) {
        $config = [
            'process' => false, // Don't use Guzzle Service's processing (we're rolling our own...)
        ];

        parent::__construct($client, $description, $config);

        if ($jobBuilder !== null) {
            $this->setJobBuilder($jobBuilder);
        }

        $emitter = $this->getEmitter();
        $emitter->attach(new Subscriber\Command\PrepareRequest($description));
        $emitter->attach(new Subscriber\Command\ProcessResponse($description));
    }

    /**
     * @return string|null
     */
    public function getServiceAppId()
    {
        return $this->getHeaderOption(self::HEADER_APP_ID);
    }

    /**
     * @return string|null
     */
    public function getServiceAppKey()
    {
        return $this->getHeaderOption(self::HEADER_APP_KEY);
    }

    /**
     * @return string
     */
    public function getServiceUrl()
    {
        return $this->getHttpClient()->getBaseUrl();
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

        // It seems we can't intercept Guzzle's request exceptions through the event system...
        // e.g. when http://api.blitline.com/ is unreachable or the request times out.
        try {
            return parent::__call($method, $args);
        } catch (\Exception $e) {
            throw Exception\OperationException::wrapException($e);
        }
    }

    /**
     * @param string $option
     * @return string|null
     */
    protected function getHeaderOption($option)
    {
        $headers = $this->getHttpClient()->getDefaultOption('headers');

        return array_key_exists($option, $headers) ? $headers[$option] : null;
    }
}

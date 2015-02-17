<?php

namespace Detail\FileConversion\Client\Subscriber;

use Guzzle\Common\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Detail\FileConversion\Client\FileConversionClient;

class RequestOptionsSubscriber implements
    EventSubscriberInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array('command.after_prepare' => 'applyRequestOptions');
    }

    /**
     * @param Event $event
     */
    public function applyRequestOptions(Event $event)
    {
        /** @var \Guzzle\Service\Command\OperationCommand $command */
        $command = $event['command'];
        $requestOptions = $command->getOperation()->getData('requestOptions');

        if (is_array($requestOptions)) {
            $request = $command->getRequest();

            /** @var FileConversionClient $client */
            $client = $command->getClient();
            $client->getRequestFactory()->applyOptions($request, $requestOptions);
        }
    }
}

<?php

namespace DetailTest\FileConversion\Client\Job\Definition;

use Detail\FileConversion\Client\Job\Definition\ActionDefinition;
use Detail\FileConversion\Client\Job\Definition\JobDefinition;
use Detail\FileConversion\Client\Job\Definition\NotificationDefinition;

class JobDefinitionTest extends DefinitionTestCase
{
    /**
     * @return string
     */
    protected function getDefinitionClass()
    {
        return JobDefinition::CLASS;
    }

    public function testSourceUrlCanBeSet()
    {
        $definition = $this->getDefinition();
        $url = 'http://www.detailnet.ch/image.jpg';

        $this->setMethodReturnValue($definition, 'getOption', $url);

        /** @var JobDefinition $definition */

        $this->assertEquals($definition, $definition->setSourceUrl($url));
        $this->assertEquals($url, $definition->getSourceUrl());
    }

    public function testActionsCanBeSet()
    {
        $definition = $this->getDefinition();
        $actionOne = new ActionDefinition();
        $actionTwo = new ActionDefinition();
        $actions = array(
            $actionOne,
            $actionTwo,
        );

        $this->setMethodReturnValue($definition, 'getOption', $actions);

        /** @var JobDefinition $definition */

        $this->assertEquals($definition, $definition->setActions(array($actionOne)));
        $this->assertEquals($definition, $definition->addAction($actionTwo));
        $this->assertEquals($actions, $definition->getActions());
    }

    public function testNotificationsCanBeSet()
    {
        $definition = $this->getDefinition();
        $notificationOne = new NotificationDefinition();
        $notificationTwo = new NotificationDefinition();
        $notifications = array(
            $notificationOne,
            $notificationTwo,
        );

        $this->setMethodReturnValue($definition, 'getOption', $notifications);

        /** @var JobDefinition $definition */

        $this->assertEquals($definition, $definition->setNotifications(array($notificationOne)));
        $this->assertEquals($definition, $definition->addNotification($notificationTwo));
        $this->assertEquals($notifications, $definition->getNotifications());
    }
}

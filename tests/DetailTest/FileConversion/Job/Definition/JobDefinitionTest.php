<?php

namespace DetailTest\FileConversion\Job\Definition;

use Detail\FileConversion\Job\Definition\ActionDefinition;
use Detail\FileConversion\Job\Definition\JobDefinition;

class JobDefinitionTest extends DefinitionTestCase
{
    protected function getDefinitionClass()
    {
        return 'Detail\FileConversion\Job\Definition\JobDefinition';
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
}

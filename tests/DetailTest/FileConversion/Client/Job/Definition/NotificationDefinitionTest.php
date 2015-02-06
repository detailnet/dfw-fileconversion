<?php

namespace DetailTest\FileConversion\Client\Job\Definition;

use Detail\FileConversion\Client\Job\Definition\NotificationDefinition;

class NotificationDefinitionTest extends DefinitionTestCase
{
    protected function getDefinitionClass()
    {
        return 'Detail\FileConversion\Client\Job\Definition\NotificationDefinition';
    }

    public function testTypeCanBeSet()
    {
        $definition = $this->getDefinition();
        $type = 'webhook';

        $this->setMethodReturnValue($definition, 'getOption', $type);

        /** @var NotificationDefinition $definition */

        $this->assertEquals($definition, $definition->setType($type));
        $this->assertEquals($type, $definition->getType());
    }

    public function testParamsCanBeSet()
    {
        $definition = $this->getDefinition();
        $params = array('a' => 'b');

        $this->setMethodReturnValue($definition, 'getOption', $params);

        /** @var NotificationDefinition $definition */

        $this->assertEquals($definition, $definition->setParams($params));
        $this->assertEquals($params, $definition->getParams());
    }
}
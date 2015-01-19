<?php

namespace DetailTest\FileConversion\Job\Definition;

use Detail\FileConversion\Job\Definition\ActionDefinition;

class NotificationDefinitionTest extends DefinitionTestCase
{
    protected function getDefinitionClass()
    {
        return 'Detail\FileConversion\Job\Definition\NotificationDefinition';
    }

    public function testTypeCanBeSet()
    {
        $definition = $this->getDefinition();
        $type = 'webhook';

        $this->setMethodReturnValue($definition, 'getOption', $type);

        /** @var ActionDefinition $definition */

        $this->assertEquals($definition, $definition->setType($type));
        $this->assertEquals($type, $definition->getType());
    }

    public function testParamsCanBeSet()
    {
        $definition = $this->getDefinition();
        $params = array('a' => 'b');

        $this->setMethodReturnValue($definition, 'getOption', $params);

        /** @var ActionDefinition $definition */

        $this->assertEquals($definition, $definition->setParams($params));
        $this->assertEquals($params, $definition->getParams());
    }
}

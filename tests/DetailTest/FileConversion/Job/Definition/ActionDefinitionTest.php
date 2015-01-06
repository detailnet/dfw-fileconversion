<?php

namespace DetailTest\FileConversion\Job\Definition;

use Detail\FileConversion\Job\Definition\ActionDefinition;

class ActionDefinitionTest extends DefinitionTestCase
{
    protected function getDefinitionClass()
    {
        return 'Detail\FileConversion\Job\Definition\ActionDefinition';
    }

    public function testNameCanBeSet()
    {
        $definition = $this->getDefinition();
        $name = 'resize';

        $this->setMethodReturnValue($definition, 'getOption', $name);

        /** @var ActionDefinition $definition */

        $this->assertEquals($definition, $definition->setName($name));
        $this->assertEquals($name, $definition->getName());
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

    public function testSaveOptionsCanBeSet()
    {
        $definition = $this->getDefinition();
        $saveOptions = array('a' => 'b');

        $this->setMethodReturnValue($definition, 'getOption', $saveOptions);

        /** @var ActionDefinition $definition */

        $this->assertEquals($definition, $definition->setSaveOptions($saveOptions));
        $this->assertEquals($saveOptions, $definition->getSaveOptions());
    }
}

<?php

namespace Detail\FileConversion\Processing\Adapter\Blitline;

interface FunctionProviderInterface
{
    public function hasFunction(string $action): bool;

    public function getFunction(string $action): Func\FunctionInterface;
}

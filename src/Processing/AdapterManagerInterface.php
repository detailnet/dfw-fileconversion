<?php

namespace Detail\FileConversion\Processing;

interface AdapterManagerInterface
{
    public function hasAdapter(string $name): bool;

    public function getAdapter(string $name, array $options = []): Adapter\AdapterInterface;
}

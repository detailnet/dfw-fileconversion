<?php

namespace Detail\FileConversion\Processing;

interface AdapterManagerInterface
{
    public function hasAdapter($name);

    public function getAdapter($name, $options = []);
}

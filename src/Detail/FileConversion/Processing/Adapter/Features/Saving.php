<?php

namespace Detail\FileConversion\Processing\Adapter\Features;

interface Saving
{
    /**
     * @param string $type
     * @return boolean
     */
    public function supportsSavingType($type);
}

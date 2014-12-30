<?php

namespace Application\Job\Application\JobProcessing\Adapter\Features;

interface Saving
{
    /**
     * @param string $type
     * @return boolean
     */
    public function supportsSavingType($type);
}

<?php

namespace Application\Job\Application\JobProcessing\Adapter;

class BlitlineAdapter extends BaseAdapter implements
//    Features\Polling,
//    Features\SynchronousProcessing,
//    Features\AsynchronousProcessing,
    Features\Saving
{
    /**
     * @param string $actionName
     * @return bool
     */
    public function supportsAction($actionName)
    {
        /** @todo Replace with real implementation */
        return true;
    }

    /**
     * @param string $type
     * @return boolean
     */
    public function supportsSavingType($type)
    {
        /** @todo Replace with real implementation */
        return true;
    }
}

<?php

namespace Application\Job\Application\JobProcessing\Adapter;

use Detail\Blitline\Client\BlitlineClient;

class BlitlineAdapter extends BaseAdapter implements
//    Features\Polling,
//    Features\SynchronousProcessing,
//    Features\AsynchronousProcessing,
    Features\Saving
{
    /**
     * @var BlitlineClient
     */
    protected $blitlineClient;

    /**
     * @param BlitlineClient $blitlineClient
     */
    public function __construct(BlitlineClient $blitlineClient)
    {
        $this->blitlineClient = $blitlineClient;
    }

    /**
     * @return BlitlineClient
     */
    public function getBlitlineClient()
    {
        return $this->blitlineClient;
    }

    /**
     * @param BlitlineClient $blitlineClient
     */
    public function setBlitlineClient($blitlineClient)
    {
        $this->blitlineClient = $blitlineClient;
    }

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

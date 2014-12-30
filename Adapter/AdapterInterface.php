<?php

namespace Application\Job\Application\JobProcessing\Adapter;

interface AdapterInterface
{
    /**
     * @param string $actionName
     * @return bool
     */
    public function supportsAction($actionName);
}

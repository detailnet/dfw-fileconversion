<?php

namespace Detail\FileConversion\Processing\Adapter;

class SupportCheck extends BaseCheck
{
    /**
     * @param array $supportedActions
     * @param array $actualActions
     * @return boolean
     */
    public function validate(array $supportedActions, array $actualActions)
    {
        $unsupportedActions = array_diff($actualActions, $supportedActions);

        if (count($unsupportedActions) > 0) {
            foreach ($unsupportedActions as $unsupportedAction) {
                $this->messages[] = sprintf('Action "%s" is not supported', $unsupportedAction);
            }

            $this->valid = false;
            return false;
        }

        return true;
    }
}

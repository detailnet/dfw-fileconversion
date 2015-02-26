<?php

namespace Detail\FileConversion\Processing\Adapter;

class ValidationCheck extends BaseCheck
{
    /**
     * @param string $action
     * @param array $requiredParams
     * @param array $actualParams
     * @return boolean
     */
    public function validate($action, array $requiredParams, array $actualParams)
    {
        $missingParams = array_diff($requiredParams, array_keys($actualParams));

        if (count($missingParams) > 0) {
            $this->messages[] = sprintf(
                'Action "%s" is missing parameters ("%s")',
                $action,
                implode('", "', $missingParams)
            );

            $this->valid = false;
            return false;
        }

        return true;
    }
}

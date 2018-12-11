<?php

namespace Detail\FileConversion\Processing;

use DateTime;

interface PausableTaskProcessorInterface
{
    /**
     * @param DateTime $until
     * @return DateTime
     */
    public function pauseProcessing(DateTime $until = null);

    /**
     * @return void
     */
    public function resumeProcessing();

    /**
     * @return DateTime|boolean
     */
    public function getPausedUntil();

    /**
     * @return boolean
     */
    public function isPaused();
}

<?php

namespace Detail\FileConversion\Processing\Action;

class ThumbnailAction extends BaseAction
{
    const NAME = 'thumbnail';

    /**
     * @var array
     */
    protected $requiredParams = array(
        'width',
        'height',
    );
}

<?php
// Copyright 1999-2019. Plesk International GmbH.

namespace PleskX\Api\Struct\Webspace;

class LimitInfo extends \PleskX\Api\Struct
{
    /** @var string */
    public $name;

    /** @var string */
    public $type;

    /** @var string */
    public $label;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'name',
            'type',
            'label',
        ]);
    }
}
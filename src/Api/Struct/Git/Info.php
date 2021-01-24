<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Git;

use PleskX\Api\Struct;

class Info extends Struct
{
    /** @var int */
    public int $uid;

    /** @var string */
    public string $url;


    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'uid',
            'url',
        ]);
    }
}

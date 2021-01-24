<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Customer;

use Exception;
use PleskX\Api\Struct;

class Info extends Struct
{
    /** @var int */
    public $id;

    /** @var string */
    public $guid;

    /**
     * Info constructor.
     * @param $apiResponse
     * @throws Exception
     */
    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'id',
            'guid',
        ]);
    }
}

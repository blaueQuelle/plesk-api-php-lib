<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Struct\Customer;

use Exception;
use PleskX\Api\Struct;

class GeneralInfo extends Struct
{
    /** @var string */
    public $company;

    /** @var string */
    public $personalName;

    /** @var string */
    public $login;

    /** @var string */
    public $guid;

    /** @var string */
    public $email;

    /** @var string */
    public $phone;

    /** @var string */
    public $fax;

    /** @var string */
    public $address;

    /** @var string */
    public $postalCode;

    /** @var string */
    public $city;

    /** @var string */
    public $state;

    /** @var string */
    public $country;

    /** @var string */
    public $description;

    /** @var string */
    public $externalId;

    /**
     * GeneralInfo constructor.
     * @param $apiResponse
     * @throws Exception
     */
    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            ['cname' => 'company'],
            ['pname' => 'personalName'],
            'login',
            'guid',
            'email',
            'phone',
            'fax',
            'address',
            ['pcode' => 'postalCode'],
            'city',
            'state',
            'country',
            'external-id',
            'description',
        ]);
    }
}

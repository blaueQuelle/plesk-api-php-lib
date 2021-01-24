<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Client;
use PleskX\Api\Client\Exception;
use PleskX\Api\Operator;
use PleskX\Api\Struct\Locale as Struct;

class Locale extends Operator
{
    /**
     * @param string|null $id
     *
     * @return Struct\Info|Struct\Info[]
     * @throws Exception
     * @throws \PleskX\Api\Exception
     */
    public function get($id = null)
    {
        $locales = [];
        $packet = $this->_client->getPacket();
        $filter = $packet->addChild($this->_wrapperTag)->addChild('get')->addChild('filter');

        if (!is_null($id)) {
            $filter->addChild('id', $id);
        }

        $response = $this->_client->request($packet, Client::RESPONSE_FULL);

        foreach ($response->locale->get->result as $localeInfo) {
            $locales[(string) $localeInfo->info->id] = new Struct\Info($localeInfo->info);
        }

        return !is_null($id) ? reset($locales) : $locales;
    }
}

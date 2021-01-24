<?php


namespace PleskX\Api\Operator;


use PleskX\Api\Operator;

class LetsEncrypt extends Operator
{

    public function create(string $name)
    {
        $packet = $this->_client->getPacket();
        $item = $packet->addChild("service-plan")->addChild("add-plan-item");
        $item->addChild("filter")->addChild("name", $name);
        $item->addChild("plan-item")->addChild("name", "urn:ext:letsencrypt:plan-item-sdk:keep-secured");

        $response = $this->_client->request($packet);
        return $response;
    }
}
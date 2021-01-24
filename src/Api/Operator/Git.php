<?php


namespace PleskX\Api\Operator;


class Git extends \PleskX\Api\Operator
{

    public function create()
    {
        $packet = $this->_client->getPacket();
        rd($this);
    }
}
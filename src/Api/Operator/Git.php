<?php


namespace PleskX\Api\Operator;

use PleskX\Api\Client\Exception;
use PleskX\Api\Operator;
use PleskX\Api\XmlResponse;

class Git extends Operator
{
    /**
     * @param string $domain
     * @param string $name
     * @param string $deploymentMode
     * @param string $deploymentPath
     * @return XmlResponse
     * @throws Exception|\PleskX\Api\Exception
     */
    public function create(string $domain, string $name, string $deploymentMode = "auto", string $deploymentPath = "httpdocs"): XmlResponse
    {
        $packet = $this->_client->getPacket();
        $info = $packet->addChild("extension")->addChild("call")->addChild("git")->addChild("create");
        $info->addChild("domain", $domain);
        $info->addChild("name", $name);
        $info->addChild("deployment-mode", $deploymentMode);
        $info->addChild("deployment-path", $deploymentPath);

        $response = $this->_client->request($packet);

        ray($this, $packet, $info, $response);

        return $response;
    }
}
<?php

namespace PleskX\Api\Operator;

use PleskX\Api\Client\Exception;
use PleskX\Api\Operator;
use PleskX\Api\Struct\Git as Struct;

class Git extends Operator
{
    /**
     * @param string $domain
     * @param string $name
     * @param string $deploymentMode
     * @param string $deploymentPath
     * @return Struct\Info
     * @throws Exception|\PleskX\Api\Exception
     */
    public function create(string $domain, string $name, string $deploymentMode = "auto", string $deploymentPath = "httpdocs"): Struct\Info
    {
        $packet = $this->_client->getPacket();
        $info = $packet->addChild("extension")->addChild("call")->addChild("git")->addChild("create");
        $info->addChild("domain", $domain);
        $info->addChild("name", $name);
        $info->addChild("deployment-mode", $deploymentMode);
        $info->addChild("deployment-path", $deploymentPath);

        $response = $this->_client->request($packet);

        return new Struct\Info($response->git->create->repository);
    }
}
<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Client;
use PleskX\Api\Exception;
use PleskX\Api\Operator;
use PleskX\Api\Struct\Database as Struct;
use PleskX\Api\XmlResponse;

class Database extends Operator
{
    /**
     * @param array $properties
     *
     * @return Struct\Info
     * @throws Exception ;
     * @throws Client\Exception
     */
    public function create(array $properties): Struct\Info
    {
        return new Struct\Info($this->_process('add-db', $properties));
    }

    /**
     * @param array $properties
     *
     * @return Struct\UserInfo
     * @throws Exception ;
     * @throws Client\Exception
     */
    public function createUser(array $properties): Struct\UserInfo
    {
        return new Struct\UserInfo($this->_process('add-db-user', $properties));
    }

    /**
     * @param string $command
     * @param array $properties
     *
     * @return XmlResponse
     * @throws Exception ;
     * @throws Client\Exception
     */
    private function _process(string $command, array $properties): XmlResponse
    {
        $packet = $this->_client->getPacket();
        $info = $packet->addChild($this->_wrapperTag)->addChild($command);

        foreach ($properties as $name => $value) {
            if (false !== strpos($value, '&')) {
                $info->$name = $value;
                continue;
            }
            $info->addChild($name, $value);
        }

        return $this->_client->request($packet);
    }

    /**
     * @param array $properties
     *
     * @return bool
     * @throws Exception;
     * @throws Client\Exception
     */
    public function updateUser(array $properties): bool
    {
        $response = $this->_process('set-db-user', $properties);

        return 'ok' === (string) $response->status;
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return Struct\Info
     * @throws Client\Exception
     * @throws Exception
     */
    public function get(string $field, $value): Struct\Info
    {
        $items = $this->getAll($field, $value);

        return reset($items);
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return Struct\UserInfo
     * @throws Client\Exception
     * @throws Exception
     */
    public function getUser(string $field, $value): Struct\UserInfo
    {
        $items = $this->getAllUsers($field, $value);

        return reset($items);
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return Struct\Info[]
     * @throws Client\Exception
     * @throws Exception
     */
    public function getAll(string $field, $value): array
    {
        $response = $this->_get('get-db', $field, $value);
        $items = [];
        foreach ($response->xpath('//result') as $xmlResult) {
            $items[] = new Struct\Info($xmlResult);
        }

        return $items;
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return Struct\UserInfo[]
     * @throws Client\Exception
     * @throws Exception
     */
    public function getAllUsers(string $field, $value): array
    {
        $response = $this->_get('get-db-users', $field, $value);
        $items = [];
        foreach ($response->xpath('//result') as $xmlResult) {
            $items[] = new Struct\UserInfo($xmlResult);
        }

        return $items;
    }

    /**
     * @param string $command
     * @param string $field
     * @param int|string $value
     *
     * @return XmlResponse
     * @throws Client\Exception
     * @throws Exception
     */
    private function _get(string $command, string $field, $value): XmlResponse
    {
        $packet = $this->_client->getPacket();
        $getTag = $packet->addChild($this->_wrapperTag)->addChild($command);

        $filterTag = $getTag->addChild('filter');
        if (!is_null($field)) {
            $filterTag->addChild($field, $value);
        }

        return $this->_client->request($packet, Client::RESPONSE_FULL);
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return bool
     * @throws Client\Exception
     * @throws Exception
     */
    public function delete(string $field, $value): bool
    {
        return $this->_delete($field, $value, 'del-db');
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return bool
     * @throws Client\Exception
     * @throws Exception
     */
    public function deleteUser(string $field, $value): bool
    {
        return $this->_delete($field, $value, 'del-db-user');
    }
}

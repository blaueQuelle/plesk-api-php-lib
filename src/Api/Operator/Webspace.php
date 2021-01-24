<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Client;
use PleskX\Api\Exception;
use PleskX\Api\Operator;
use PleskX\Api\Struct\Webspace as Struct;

class Webspace extends Operator
{
    /**
     * @return Struct\PermissionDescriptor
     * @throws Exception
     * @throws Client\Exception
     */
    public function getPermissionDescriptor(): Struct\PermissionDescriptor
    {
        $response = $this->request('get-permission-descriptor.filter');

        return new Struct\PermissionDescriptor($response);
    }

    /**
     * @return Struct\LimitDescriptor
     * @throws Exception
     * @throws Client\Exception
     */
    public function getLimitDescriptor(): Struct\LimitDescriptor
    {
        $response = $this->request('get-limit-descriptor.filter');

        return new Struct\LimitDescriptor($response);
    }

    /**
     * @return Struct\PhysicalHostingDescriptor
     * @throws Exception
     * @throws Client\Exception
     */
    public function getPhysicalHostingDescriptor(): Struct\PhysicalHostingDescriptor
    {
        $response = $this->request('get-physical-hosting-descriptor.filter');

        return new Struct\PhysicalHostingDescriptor($response);
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return Struct\PhpSettings
     * @throws Exception
     * @throws Client\Exception
     */
    public function getPhpSettings(string $field, $value): Struct\PhpSettings
    {
        $packet = $this->_client->getPacket();
        $getTag = $packet->addChild($this->_wrapperTag)->addChild('get');

        $getTag->addChild('filter')->addChild($field, $value);
        $getTag->addChild('dataset')->addChild('php-settings');

        $response = $this->_client->request($packet, Client::RESPONSE_FULL);

        return new Struct\PhpSettings($response);
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return Struct\Limits
     */
    public function getLimits(string $field, $value): Struct\Limits
    {
        $items = $this->_getItems(Struct\Limits::class, 'limits', $field, $value);

        return reset($items);
    }

    /**
     * @param array $properties
     * @param array|null $hostingProperties
     * @param $planName
     *
     * @return Struct\Info
     * @throws Exception
     * @throws Client\Exception
     */
    public function create(array $properties, array $hostingProperties = null, $planName = null): Struct\Info
    {
        $packet = $this->_client->getPacket();
        $info = $packet->addChild($this->_wrapperTag)->addChild('add');

        $infoGeneral = $info->addChild('gen_setup');
        foreach ($properties as $name => $value) {
            $infoGeneral->addChild($name, $value);
        }

        if ($hostingProperties) {
            $infoHosting = $info->addChild('hosting')->addChild('vrt_hst');
            foreach ($hostingProperties as $name => $value) {
                $property = $infoHosting->addChild('property');
                $property->addChild('name', $name);
                $property->addChild('value', $value);
            }

            if (isset($properties['ip_address'])) {
                $infoHosting->addChild('ip_address', $properties['ip_address']);
            }
        }

        if ($planName) {
            $info->addChild('plan-name', $planName);
        }

        $response = $this->_client->request($packet);

        return new Struct\Info($response, $properties['name'] ?? '');
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
        return $this->_delete($field, $value);
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return Struct\GeneralInfo
     */
    public function get(string $field, $value): Struct\GeneralInfo
    {
        $items = $this->_getItems(Struct\GeneralInfo::class, 'gen_info', $field, $value);

        return reset($items);
    }

    /**
     * @return Struct\GeneralInfo[]
     */
    public function getAll()
    {
        return $this->_getItems(Struct\GeneralInfo::class, 'gen_info');
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return Struct\DiskUsage
     */
    public function getDiskUsage($field, $value)
    {
        $items = $this->_getItems(Struct\DiskUsage::class, 'disk_usage', $field, $value);

        return reset($items);
    }
}

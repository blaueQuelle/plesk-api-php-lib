<?php
// Copyright 1999-2021. Plesk International GmbH.

namespace PleskX\Api;

class Operator
{
    /** @var string|null */
    protected $_wrapperTag = null;

    /** @var Client */
    protected $_client;

    public function __construct($client)
    {
        $this->_client = $client;

        if (is_null($this->_wrapperTag)) {
            $classNameParts = explode('\\', get_class($this));
            $this->_wrapperTag = end($classNameParts);
            $this->_wrapperTag = strtolower(preg_replace('/([a-z])([A-Z])/', '\1-\2', $this->_wrapperTag));
        }
    }

    /**
     * Perform plain API request.
     *
     * @param string|array $request
     * @param int $mode
     *
     * @return XmlResponse
     * @throws \PleskX\Api\Client\Exception|Exception
     */
    public function request($request, $mode = Client::RESPONSE_SHORT): XmlResponse
    {
        $wrapperTag = $this->_wrapperTag;

        if (is_array($request)) {
            $request = [$wrapperTag => $request];
        } elseif (preg_match('/^[a-z]/', $request)) {
            $request = "$wrapperTag.$request";
        } else {
            $request = "<$wrapperTag>$request</$wrapperTag>";
        }

        return $this->_client->request($request, $mode);
    }

    /**
     * @param string $field
     * @param int|string $value
     * @param string $deleteMethodName
     *
     * @return bool
     * @throws Client\Exception
     * @throws Exception
     */
    protected function _delete(string $field, $value, $deleteMethodName = 'del'): bool
    {
        $response = $this->request([
            $deleteMethodName => [
                'filter' => [
                    $field => $value,
                ],
            ],
        ]);

        return 'ok' === (string) $response->status;
    }

    /**
     * @param string $structClass
     * @param string $infoTag
     * @param string|null $field
     * @param int|string|null $value
     * @param callable|null $filter
     *
     * @return mixed
     * @throws Exception ;
     * @throws Client\Exception ;
     */
    protected function _getItems(string $structClass, string $infoTag, $field = null, $value = null, callable $filter = null): array
    {
        $packet = $this->_client->getPacket();
        $getTag = $packet->addChild($this->_wrapperTag)->addChild('get');

        $filterTag = $getTag->addChild('filter');
        if (!is_null($field)) {
            $filterTag->addChild($field, $value);
        }

        $getTag->addChild('dataset')->addChild($infoTag);

        $response = $this->_client->request($packet, Client::RESPONSE_FULL);

        $items = [];
        foreach ($response->xpath('//result') as $xmlResult) {
            if (!is_null($filter) && !$filter($xmlResult->data->$infoTag)) {
                continue;
            }
            if (!isset($xmlResult->data) || !isset($xmlResult->data->$infoTag)) {
                continue;
            }
            $items[] = new $structClass($xmlResult->data->$infoTag);
        }

        return $items;
    }
}

<?php
/**
 * Copyright 2016 Eric Enold <zyberspace@zyberware.org>
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace Zyberspace\SteamWebApi;

use GuzzleHttp\ClientInterface;

class Client
{
    const BASE_URL = 'https://api.steampowered.com/';

    /**
     * @var string
     */
    protected $_apiKey;

    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $_httpClient;
    /**
     * @var array
     */
    protected $_clientOptions;

    public function __construct($apiKey, $clientOptions = array())
    {
        $this->_apiKey = $apiKey;
        $this->_clientOptions = $clientOptions;
    }

    /**
     * @param ClientInterface $httpClient
     * @return $this
     */
    public function setHttpClient(ClientInterface $httpClient)
    {
        $this->_httpClient = $httpClient;
        return $this;
    }

    /**
     * @return \GuzzleHttp\ClientInterface
     */
    public function getHttpClient()
    {
        if ($this->_httpClient === null) {
            $this->_httpClient = $this->createDefaultClient();
        }
        return $this->_httpClient;
    }

    /**
     * @return \GuzzleHttp\Client
     */
    protected function createDefaultClient()
    {
        return new \GuzzleHttp\Client(array_merge(array('base_uri' => self::BASE_URL), $this->_clientOptions));
    }

    public function call($interface, $method, $version, $httpMethod, $parameters)
    {
        $path = implode('/', array($interface, $method, 'v' . $version));

        $parameters['key'] = $this->_apiKey;
        $response = $this->getHttpClient()->request($httpMethod, $path, array(
            'query' => $parameters
        ));

        return json_decode($response->getBody()->getContents());
    }
}

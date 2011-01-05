<?php
/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bundle\Sonata\GoutteBundle;

use Bundle\GoutteBundle\Goutte\Client;
use Symfony\Component\BrowserKit\History;
use Symfony\Component\BrowserKit\CookieJar;

class Manager
{
    protected $client_configurations = array();

    public function getNamedClient($name)
    {
        $configuration = $this->getClientConfiguration($name);

        if(!$configuration) {
            return false;
        }
        
        return $this->getClient($configuration['config'], $configuration['server']);
    }

    public function addClientConfiguration($name, $config)
    {
        $this->client_configurations[$name] = $config;
    }

    public function getClient(array $zendConfig = array(), array $server = array(), History $history = null, CookieJar $cookieJar = null)
    {

        return new Client($zendConfig, $server, $history, $cookieJar);
    }

    public function setClientConfiguration($client_configurations)
    {
        $this->client_configurations = $client_configurations;
    }

    public function getClientConfigurations()
    {
        return $this->client_configurations;
    }

    public function getClientConfiguration($name)
    {

        return isset($this->client_configurations[$name]) ? $this->client_configurations[$name] : false;
    }
}
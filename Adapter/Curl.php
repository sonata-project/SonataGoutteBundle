<?php

/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bundle\GoutteBundle\Adapter;

use Zend\Http\Client\Adapter\Curl as BaseCurl;

class Curl extends BaseCurl
{

    protected $fh;

    /**
     * Initialize curl
     *
     * @param  string  $host
     * @param  int     $port
     * @param  boolean $secure
     * @return void
     * @throws \Zend\Http\Client\Adapter\Exception if unable to connect
     */
    public function connect($host, $port = 80, $secure = false)
    {
        parent::connect($host, $port, $secure);

                // verbose execution?
        if (isset($this->_config['verbose']) && $this->_config['verbose'])
        {
            curl_setopt($this->_curl, CURLOPT_NOPROGRESS, false);
            curl_setopt($this->_curl, CURLOPT_VERBOSE, true);
        }

        if (isset($this->_config['verbose_log']))
        {
            curl_setopt($this->_curl, CURLOPT_VERBOSE, true);
            $this->fh = fopen($this->_config['verbose_log'], 'a+b');
            curl_setopt($this->_curl, CURLOPT_STDERR, $this->fh);
        }
    }

    /**
     * Close the connection to the server
     *
     */
    public function close()
    {
        parent::close();

        if(is_resource($this->fh)) {
            fclose($this->fh);
        }

    }


    /**
     * Send request to the remote server
     *
     * @param  string        $method
     * @param  \Zend\Uri\Url $uri
     * @param  float         $http_ver
     * @param  array         $headers
     * @param  string        $body
     * @return string        $request
     * @throws \Zend\Http\Client\Adapter\Exception If connection fails, connected to wrong host, no PUT file defined, unsupported method, or unsupported cURL option
     */
    public function write($method, $uri, $httpVersion = 1.1, $headers = array(), $body = '')
    {
        $request = parent::write($method, $uri, $httpVersion, $headers, $body);

        // todo : there is something wrong with my configuration, the curl verbose mode should do the same ...
        if($this->fh) {
            fwrite($this->fh, "\n>>>> REQUEST >>>>\n");
            fwrite($this->fh, $request);
            fwrite($this->fh, "\n>>>> END >>>>\n");

            fwrite($this->fh, "\n<<<< RESPONSE <<<<\n");
            fwrite($this->fh, $this->_response);
            fwrite($this->fh, "\n<<<< END <<<<\n");
        }

        return $request;
    }
}
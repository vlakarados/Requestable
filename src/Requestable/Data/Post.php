<?php
/**
 * Parses the data of the request to create an object containing all the info to fire requests to services
 *
 * PHP version 5.4
 *
 * @category   Requestable
 * @package    Data
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 * @copyright  Copyright (c) 2013 Pieter Hordijk
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    1.0.0
 */
namespace Requestable\Data;

use Requestable\Network\Http\RequestData;

/**
 * Parses the data of the request to create an object containing all the info to fire requests to services
 *
 * @category   Requestable
 * @package    Data
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 */
class Post implements Request
{
    /**
     * @var \Requestable\Network\Http\RequestData The data of the request
     */
    private $request;

    /**
     * Creates instance
     *
     * @param \Requestable\Network\Http\RequestData $request The data of the request
     */
    public function __construct(RequestData $request)
    {
        $this->request = $request;
    }

    /**
     * Gets the URI supplied by the user
     *
     * @return string The URI supplied by the user
     */
    public function getUri()
    {
        return strtok($this->request->post('uri'), '#');
    }

    /**
     * Gets the HTTP version supplied by the user
     *
     * @return string The HTTP version supplied by the user
     */
    public function getVersion()
    {
        if ($this->request->post('version') === null) {
            return '1.1';
        }

        return $this->request->post('version');
    }

    /**
     * Gets the method supplied by the user
     *
     * @return string The method supplied by the user
     */
    public function getMethod()
    {
        if ($this->request->post('custommethod')) {
            return strtoupper($this->request->post('custommethod'));
        }

        return strtoupper($this->request->post('method'));
    }

    /**
     * Gets whether redirects needs to be followed
     *
     * @return boolean Whether redirects needs to be followed
     */
    public function redirectsEnabled()
    {
        if ($this->request->post('follow')) {
            return true;
        }

        return false;
    }

    /**
     * Gets whether cookies are enabled
     *
     * @return boolean Whether cookies are enabled
     */
    public function cookiesEnabled()
    {
        if ($this->request->post('cookies')) {
            return true;
        }

        return false;
    }

    /**
     * Gets the headers supplied by the user
     *
     * @return array The headers supplied by the user
     */
    public function getHeaders()
    {
        if (!$this->request->post('headers')) {
            return [];
        }

        $headers = [];
        foreach (preg_split('/\r?\n(?![ \t])/', $this->request->post('headers'), -1, PREG_SPLIT_NO_EMPTY) as $header) {
            list($key, $val) = preg_split('/\s*:\s*/', $header, 2);
            $headers[strtolower($key)][] = $val;
        }
        $headers['connection'] = ['close'];

        return $headers;
    }

    /**
     * Gets the body supplied by the user
     *
     * @return string The body supplied by the user
     */
    public function getBody()
    {
        return $this->request->post('body');
    }

    /**
     * Gets whether to verify the peer's certificate.
     *
     * @return boolean Whether to verify the peer's certificate
     */
    public function verifyPeer()
    {
        if ($this->request->post('verifypeer')) {
            return true;
        }

        return false;
    }

    /**
     * Gets whether to check the existence of a common name and also verify that it matches the hostname provided
     *
     * @return boolean Whether to check the existence of a common name and also
     *                 verify that it matches the hostname provided
     */
    public function verifyHost()
    {
        if ($this->request->post('verifyhost')) {
            return true;
        }

        return false;
    }

    /**
     * Gets the SSL version
     *
     * @return string|int The SSL version
     */
    public function getSslVersion()
    {
        if ($this->request->post('sslversion') === null) {
            return 'automatic';
        }

        return $this->request->post('sslversion');
    }

    /**
     * Gets the custom ca bundle
     *
     * @return null|string The custom ca bundle
     */
    public function getCaBundle()
    {
        return null;
    }

    /**
     * Gets the optional password to protect requests
     *
     * @return null|string The password to protect the request
     */
    public function getPassword()
    {
        if ($this->request->post('password') === '') {
            return null;
        }

        return $this->request->post('password');
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: eValor
 * Date: 2019-02-21
 * Time: 01:26
 */

namespace evalor\coRequests;

/**
 * URL部件
 * Class CoUrlParts
 * @package evalor\coRequests
 */
class CoUrlParts
{
    protected $url;
    protected $scheme;
    protected $host;
    protected $port;
    protected $user;
    protected $pass;
    protected $path;
    protected $query;
    protected $fragment;

    /**
     * CoUrlParts constructor.
     * @param $url
     */
    function __construct($url)
    {
        $this->url = $url;
        $urlParts = parse_url($url);

        $this->host = $urlParts['host'] ?? '';
        $this->port = $urlParts['port'] ?? '';
        $this->user = $urlParts['user'] ?? '';
        $this->pass = $urlParts['pass'] ?? '';
        $this->path = $urlParts['path'] ?? '';
        $this->query = $urlParts['query'] ?? '';
        $this->scheme = $urlParts['scheme'] ?? '';
        $this->fragment = $urlParts['fragment'] ?? '';
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * @param string $scheme
     */
    public function setScheme(string $scheme)
    {
        $this->scheme = $scheme;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @param string $host
     */
    public function setHost(string $host)
    {
        $this->host = $host;
    }

    /**
     * @return string
     */
    public function getPort(): string
    {
        return $this->port;
    }

    /**
     * @param string $port
     */
    public function setPort(string $port)
    {
        $this->port = $port;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @param string $user
     */
    public function setUser(string $user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getPass(): string
    {
        return $this->pass;
    }

    /**
     * @param string $pass
     */
    public function setPass(string $pass)
    {
        $this->pass = $pass;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * @param string $query
     */
    public function setQuery(string $query)
    {
        $this->query = $query;
    }

    /**
     * @return string
     */
    public function getFragment(): string
    {
        return $this->fragment;
    }

    /**
     * @param string $fragment
     */
    public function setFragment(string $fragment)
    {
        $this->fragment = $fragment;
    }

    /**
     * 返回一个请求的URI部分
     * @return string
     */
    public function getUri()
    {
        $path = $this->getPath() == '' ? '/' : $this->getPath();
        return $this->getQuery() == '' ? $path : $path . '?' . $this->getQuery();
    }
}
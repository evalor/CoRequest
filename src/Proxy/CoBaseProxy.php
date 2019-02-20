<?php
/**
 * Created by PhpStorm.
 * User: eValor
 * Date: 2019-02-20
 * Time: 23:46
 */

namespace evalor\coRequests\Proxy;

/**
 * 请求代理参数
 * Class CoBaseProxy
 * @package evalor\coRequests\Proxy
 */
abstract class CoBaseProxy
{
    protected $host;
    protected $port;
    protected $user;
    protected $password;

    function __construct($host, $port, $user = null, $password = null)
    {
        $this->setHost($host);
        $this->setPort($port);
        $this->setUser($user);
        $this->setPassword($password);
    }

    /**
     * 获取代理地址
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * 设置代理地址
     * @param string $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * 获取代理端口
     * @return mixed
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * 设置代理端口
     * @param mixed $port
     */
    public function setPort($port)
    {
        $this->port = $port;
    }

    /**
     * 获取代理认证用户
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * 设置代理认证用户
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * 获取代理认证密码
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * 设置代理认证密码
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * 获取该代理的客户端设置项
     * @return array
     */
    abstract function getSwooleClientSet(): array;
}
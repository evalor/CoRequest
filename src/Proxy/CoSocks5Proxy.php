<?php
/**
 * Created by PhpStorm.
 * User: eValor
 * Date: 2019-02-20
 * Time: 23:45
 */

namespace evalor\coRequests\Proxy;

/**
 * 请求Socks5代理
 * Class CoSocket5Proxy
 * @package evalor\coRequests\Proxy
 */
class CoSocks5Proxy extends CoBaseProxy
{
    /**
     * 获取该代理的客户端设置项
     * @return array
     */
    function getSwooleClientSet(): array
    {
        $set = array();
        if (!empty($this->host)) $set['socks5_host'] = $this->host;
        if (!empty($this->port)) $set['socks5_port'] = $this->port;
        if (!empty($this->user)) $set['socks5_username'] = $this->user;
        if (!empty($this->password)) $set['socks5_password'] = $this->password;
        return $set;
    }
}
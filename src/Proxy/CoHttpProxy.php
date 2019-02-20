<?php
/**
 * Created by PhpStorm.
 * User: eValor
 * Date: 2019-02-20
 * Time: 23:45
 */

namespace evalor\coRequests\Proxy;

/**
 * 请求HTTP代理
 * Class CoHttpProxy
 * @package evalor\coRequests\Proxy
 */
class CoHttpProxy extends CoBaseProxy
{
    /**
     * 获取该代理的客户端设置项
     * @return array
     */
    function getSwooleClientSet(): array
    {
        $set = array();
        if (!empty($this->host)) $set['http_proxy_host'] = $this->host;
        if (!empty($this->port)) $set['http_proxy_port'] = $this->port;
        if (!empty($this->user)) $set['http_proxy_user'] = $this->user;
        if (!empty($this->password)) $set['http_proxy_password'] = $this->password;
        return $set;
    }
}
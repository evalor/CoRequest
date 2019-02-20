<?php
/**
 * Created by PhpStorm.
 * User: eValor
 * Date: 2019-02-20
 * Time: 23:21
 */

namespace evalor\coRequests\Cookie;

/**
 * CookieJar
 * Class CoCookieJar
 * @package evalor\coRequests
 */
class CoCookieJar
{
    protected $cookies = array();

    /**
     * 添加一个Cookie(同名将被覆盖)
     * @param string $name
     * @param string $value
     * @param int $expire
     * @param string $path
     * @param null|string $domain
     * @param bool $secure
     * @param bool $httpOnly
     * @return CoCookieJar
     */
    function addCookie($name, $value, $expire = 0, $path = '/', $domain = null, $secure = false, $httpOnly = false)
    {
        $cookie = new CoCookie;
        $cookie->setName($name);
        $cookie->setValue($value);
        $cookie->setExpire($expire);
        $cookie->setPath($path);
        $cookie->setDomain($domain);
        $cookie->setSecure($secure);
        $cookie->setHttpOnly($httpOnly);
        $this->cookies[$name] = $cookie;
        return $this;
    }

    /**
     * 从一个字符串添加Cookie(同名将被覆盖)
     * @param string $cookieStr
     * @return $this
     */
    function addCookieStr($cookieStr)
    {
        $cookie = new CoCookie;
        $cookie->parserCookieStr($cookieStr);
        $this->cookies[$cookie->getName()] = $cookie;
        return $this;
    }

    /**
     * 删除一个Cookie
     * @param string $name
     */
    function deleteCookie($name)
    {
        unset($this->cookies[$name]);
    }

    /**
     * 获取一个Cookie
     * @param string $name
     * @return CoCookie|null
     */
    function getCookie($name)
    {
        return $this->cookies[$name] ?? null;
    }

    /**
     * 返回响应头的SetCookie格式
     * @return array
     */
    function formatForSetCookie(): array
    {
        $set = array();
        foreach ($this->cookies as $cookie) {
            $set[] = 'Set-Cookie: ' . $cookie->formatForSetCookie();
        }
        return $set;
    }

    /**
     * 返回请求头的Header格式
     * @return string
     */
    function formatForHeader()
    {
        $cookieStr = '';
        foreach ($this->cookies as $cookie) {
            $cookieStr .= $cookie->__toString();
        }
        return $cookieStr;
    }

    /**
     * 返回数组格式
     * @return array
     */
    function formatForArray()
    {
        $cookieArr = array();
        foreach ($this->cookies as $cookie) {
            $cookieArr[$cookie->getName()] = $cookie->getValue();
        }
        return $cookieArr;
    }

    /**
     * 获取全部的Cookie
     * @return array
     */
    function getCookies(): array
    {
        return $this->cookies;
    }

    /**
     * 是否存在Cookie
     * @return bool
     */
    function isEmpty()
    {
        return empty($this->cookies);
    }
}
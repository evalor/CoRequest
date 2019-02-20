<?php
/**
 * Created by PhpStorm.
 * User: eValor
 * Date: 2019-02-20
 * Time: 23:33
 */

namespace evalor\coRequests\Cookie;

/**
 * Cookie
 * Class CoCookie
 * @package evalor\coRequests\Cookie
 */
class CoCookie
{
    private $name;
    private $value;
    private $expire = 0;
    private $path = '/';
    private $domain = null;
    private $secure = false;
    private $httpOnly = false;

    /**
     * 解析请求头携带的Cookie字符串
     * @param string $cookieStr
     * @return CoCookie
     */
    function parserCookieStr($cookieStr): CoCookie
    {
        $cookieParts = explode(';', $cookieStr);
        $cookieKv = array_shift($cookieParts);

        // 解析键和值
        if (strpos($cookieKv, '=') == false) {
            $this->name = '';
            $this->value = $cookieKv;
        } else {
            list($this->name, $this->value) = explode('=', $cookieKv, 2);
            $this->name = trim($this->name);
            $this->value = trim($this->value);
        }

        // 解析其他参数
        if (!empty($cookieParts)) {
            foreach ($cookieParts as $cookiePart) {
                if (strpos($cookiePart, '=') == false) {
                    $cookiePartName = $cookiePart;
                    $cookiePartValue = true;
                } else {
                    list($cookiePartName, $cookiePartValue) = explode('=', $cookiePart, 2);
                }

                // 属性不区分大小写
                $cookiePartName = trim(strtolower($cookiePartName));
                $cookiePartValue = trim(strtolower($cookiePartValue));

                if ($cookiePartName == 'httponly') {
                    $this->httpOnly = true;
                } else {
                    $this->$cookiePartName = $cookiePartValue;
                }
            }
        }
        return $this;
    }

    /**
     * 用作SetCookie时的格式
     * @return string
     */
    function formatForSetCookie(): string
    {
        $setCookieStr = sprintf('%s=%s; %s=%s', $this->name, $this->value, 'path', $this->path);
        if ($this->expire != 0) $setCookieStr .= sprintf('; %s=%s', 'expire', $this->expire);
        if ($this->domain != null) $setCookieStr .= sprintf('; %s=%s', 'domain', $this->domain);
        if ($this->secure) $setCookieStr .= '; Secure';
        if ($this->httpOnly) $setCookieStr .= '; HttpOnly';
        return $setCookieStr;
    }

    /**
     * 用作请求头部的格式
     * @return string
     */
    function formatForHeader(): string
    {
        return sprintf('%s=%s', $this->name, $this->value);
    }

    /**
     * 被用作字符串时的处理
     * @return string
     */
    function __toString()
    {
        return $this->formatForHeader();
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getExpire(): int
    {
        return $this->expire;
    }

    /**
     * @param int $expire
     */
    public function setExpire(int $expire)
    {
        $this->expire = $expire;
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
     * @return null
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param null $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * @return bool
     */
    public function isSecure(): bool
    {
        return $this->secure;
    }

    /**
     * @param bool $secure
     */
    public function setSecure(bool $secure)
    {
        $this->secure = $secure;
    }

    /**
     * @return bool
     */
    public function isHttpOnly(): bool
    {
        return $this->httpOnly;
    }

    /**
     * @param bool $httpOnly
     */
    public function setHttpOnly(bool $httpOnly)
    {
        $this->httpOnly = $httpOnly;
    }
}
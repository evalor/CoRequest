<?php
/**
 * Created by PhpStorm.
 * User: eValor
 * Date: 2019-02-20
 * Time: 23:20
 */

namespace evalor\coRequests;

use evalor\coRequests\Cookie\CoCookie;
use evalor\coRequests\Cookie\CoCookieJar;
use Swoole\Coroutine\Http\Client;

/**
 * 协程客户端响应
 * Class CoResponse
 * @package evalor\coRequests
 */
class CoResponse
{
    protected $error;
    protected $errno;
    protected $statusCode;
    protected $headers;
    protected $cookies;
    protected $content;
    protected $coClient;

    /**
     * CoResponse constructor.
     * @param Client $client
     */
    function __construct(Client $client)
    {
        $this->coClient = $client;
        $this->errno = $client->errCode;
        $this->error = $client->errCode == 0 ? '' : socket_strerror($client->errCode);
        $this->statusCode = $client->statusCode;
        $this->headers = $client->headers;
        $this->cookies = new CoCookieJar;
        $this->content = $client->body;

        // 处理Cookie
        if ($client->set_cookie_headers) {
            foreach ($client->set_cookie_headers as $cookie_header) {
                $this->cookies->addCookieStr($cookie_header);
            }
        }
    }

    // -----------  辅助方法 ----------- //

    /**
     * 是否存在错误
     * @return bool
     */
    function hasError()
    {
        return $this->errno !== 0;
    }

    /**
     * 返回的是JSON响应 解出数组
     * @return array|null
     */
    function parserJson(): ?array
    {
        $data = json_decode($this->content, true);
        $errno = json_last_error();
        if ($errno) {
            $this->errno = $errno;
            $this->error = json_last_error_msg();
            return null;
        }
        return $data;
    }

    /**
     * 返回的是XML响应 解出数组
     * @return array|null
     */
    function parserXml(): ?array
    {
        $xml = simplexml_load_string($this->content);
        if ($xml) {
            return json_decode(json_encode($xml), true);
        }
        return null;
    }

    // -----------  Getter ----------- //

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * @return mixed
     */
    public function getErrno()
    {
        return $this->errno;
    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return mixed
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param $name
     * @return |null
     */
    public function getHeader($name)
    {
        return $this->headers[strtolower($name)] ?? null;
    }

    /**
     * @return CoCookieJar
     */
    public function getCookies(): CoCookieJar
    {
        return $this->cookies;
    }

    /**
     * @param $name
     * @return CoCookie|null
     */
    public function getCookie($name): ?CoCookie
    {
        return $this->cookies[strtolower($name)] ?? null;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return Client
     */
    public function getCoClient(): Client
    {
        return $this->coClient;
    }
}
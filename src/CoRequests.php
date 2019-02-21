<?php
/**
 * Created by PhpStorm.
 * User: eValor
 * Date: 2019-02-20
 * Time: 23:14
 */

namespace evalor\coRequests;

use evalor\coRequests\Cookie\CoCookieJar;
use evalor\coRequests\Exception\InvalidUrl;
use evalor\coRequests\Proxy\CoBaseProxy;
use evalor\coRequests\Proxy\CoHttpProxy;
use evalor\coRequests\Proxy\CoSocks5Proxy;
use Swoole\Coroutine\Http\Client;

/**
 * 协程请求客户端
 * Class CoRequests
 * @package evalor\coRequests
 */
class CoRequests
{
    /** @var CoUrlParts 请求的URL */
    protected $url;

    /** @var array 请求客户端的参数 */
    protected $clientSetting;

    /** @var float 请求链接超时 */
    protected $connectTimeout;

    /** @var float 请求等待超时 */
    protected $requestTimeout;

    /** @var array 请求的POST数据 */
    protected $clientPostData = array();

    /** @var array 请求上传的文件 */
    protected $clientPostFiles = array();

    /** @var array 请求的Header头部 */
    protected $headers = array(
        "user-agent" => "SwooleCoRequests/1.0",
        "accept-encoding" => "gzip, deflate, br",
        "cache-control" => "no-cache",
        "pragma" => "no-cache",
    );

    /** @var CoCookieJar 请求携带的Cookie */
    protected $cookies;

    /** @var bool 是否启用POST请求 */
    protected $isPost = false;

    /**
     * CoRequests constructor.
     * @param $url
     * @throws InvalidUrl
     */
    function __construct($url)
    {
        if (!empty($url)) {
            $this->setUrl($url);
        }
        $this->setConnectTimeout(3);
        $this->setRequestTimeout(5);
        $this->setCookieJar(new CoCookieJar);
    }

    // -----------  客户端设置 ----------- //

    /**
     * 直接设置请求客户端的参数
     * @param string $name
     * @param mixed $value
     * @return CoRequests
     */
    function setClientSetting(string $name, $value): CoRequests
    {
        $this->clientSetting[$name] = $value;
        return $this;
    }

    /**
     * 设置请求URL
     * @param string $url
     * @return CoRequests
     * @throws InvalidUrl
     */
    function setUrl(string $url): CoRequests
    {
        $this->url = new CoUrlParts($url);
        if (empty($this->url->getHost())) {
            throw new InvalidUrl("url: {$url} is invalid");
        }
        return $this;
    }

    /**
     * 设置SSL证书
     * @param string $filePath
     * @return CoRequests
     */
    function setSslCertFile(string $filePath): CoRequests
    {
        if (is_file($filePath)) {
            $this->setClientSetting('ssl_cert_file', $filePath);
        }
        return $this;
    }

    /**
     * 设置SSL秘钥
     * @param string $filePath
     * @return CoRequests
     */
    function setSslKeyFile(string $filePath): CoRequests
    {
        if (is_file($filePath)) {
            $this->setClientSetting('ssl_key_file', $filePath);
        }
        return $this;
    }

    /**
     * 开启服务器端证书验证
     * @param bool $enable
     * @return CoRequests
     */
    function setSslVerifyPeer(bool $enable = true): CoRequests
    {
        $this->setClientSetting('ssl_verify_peer', $enable);
        return $this;
    }

    /**
     * 允许自签名证书
     * @param bool $enable
     * @return CoRequests
     */
    function setSslAllowSelfSigned(bool $enable = true): CoRequests
    {
        $this->setClientSetting('ssl_allow_self_signed', $enable);
        return $this;
    }

    /**
     * 设置服务器主机名称
     * @param string $hostname
     * @return CoRequests
     */
    function setSslHostName(string $hostname): CoRequests
    {
        $this->setClientSetting('ssl_host_name', $hostname);
        return $this;
    }

    /**
     * 验证远端证书所用到的CA证书
     * @param string $filePath
     * @return CoRequests
     */
    function setSslCafile(string $filePath): CoRequests
    {
        if (is_file($filePath)) {
            $this->setClientSetting('ssl_cafile', $filePath);
        }
        return $this;
    }

    /**
     * CA证书搜索目录
     * @param string $dirPath
     * @return CoRequests
     */
    function setSslCaPath(string $dirPath): CoRequests
    {
        if (is_dir($dirPath)) {
            $this->setClientSetting('ssl_capath', $dirPath);
        }
        return $this;
    }

    /**
     * 设置请求代理
     * @param CoHttpProxy|CoSocks5Proxy $coProxy
     * @return CoRequests
     */
    function setProxy($coProxy): CoRequests
    {
        if ($coProxy instanceof CoBaseProxy) {
            $this->clientSetting = $this->clientSetting + $coProxy->getSwooleClientSet();
        }
        return $this;
    }

    /**
     * 设置链接超时
     * @param float $timeout 单位秒
     * @return CoRequests
     */
    function setConnectTimeout(float $timeout = 3.0): CoRequests
    {
        $this->setClientSetting('connect_timeout', $timeout);
        return $this;
    }

    /**
     * 设置请求超时
     * @param float $timeout 单位秒
     * @return CoRequests
     */
    function setRequestTimeout(float $timeout = 5.0): CoRequests
    {
        $this->setClientSetting('timeout', $timeout);
        return $this;
    }


    /**
     * 是否开启长链接
     * @param bool $enable
     * @return CoRequests
     */
    function setKeepAlive(bool $enable = true): CoRequests
    {
        $this->setClientSetting('keep_alive', $enable);
        return $this;
    }

    // -----------  请求体设置 ----------- //

    /**
     * 设置单个请求头
     * @param string $name
     * @param string $value
     * @return CoRequests
     */
    function setHeader(string $name, string $value): CoRequests
    {
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * 设置全部请求头
     * @param array $headers
     * @return CoRequests
     */
    function setHeaders(array $headers): CoRequests
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * 设置单个Cookie
     * @param string $name
     * @param string $value
     * @return CoRequests
     */
    function setCookie(string $name, string $value): CoRequests
    {
        $this->cookies->addCookie($name, $value);
        return $this;
    }

    /**
     * 使用CookieJar设置全部Cookie
     * 可于请求Response拿到登陆CookieJar再次发起新请求
     * @param CoCookieJar $coCookieJar
     * @return CoRequests
     */
    function setCookieJar(CoCookieJar $coCookieJar): CoRequests
    {
        $this->cookies = $coCookieJar;
        return $this;
    }

    /**
     * 添加POST数据
     * @param string $name
     * @param string $value
     * @return CoRequests
     */
    function addPostData(string $name, string $value): CoRequests
    {
        $this->setIsPost();
        $this->clientPostData[$name] = $value;
        return $this;
    }

    /**
     * 整体替换POST数据
     * @param $postData
     * @return CoRequests
     */
    function setPostData($postData): CoRequests
    {
        $this->setIsPost();
        $this->clientPostData = $postData;
        return $this;
    }

    /**
     * 添加上传文件
     * @param CoPostFile $coPostFile
     * @return CoRequests
     */
    function setPostFile(CoPostFile $coPostFile): CoRequests
    {
        $this->setIsPost();
        $this->clientPostFiles[$coPostFile->getName()] = $coPostFile;
        return $this;
    }

    /**
     * 批量修改上传文件
     * @param array $postFiles
     * @return CoRequests
     */
    function setPostFiles(array $postFiles): CoRequests
    {
        $this->setIsPost();
        $this->clientPostFiles = $postFiles;
        return $this;
    }

    /**
     * 当前为POST请求
     * 如果自定义请求可以使用该参数开启POST
     * @param bool $isPost
     * @return CoRequests
     */
    function setIsPost($isPost = true): CoRequests
    {
        $this->isPost = $isPost;
        return $this;
    }

    /**
     * 执行请求
     * @param float|null $responseTimeout
     * @return CoResponse
     * @throws InvalidUrl
     */
    function exec($responseTimeout = null)
    {
        $requestUri = $this->url->getUri();
        if ($responseTimeout !== null) {
            $this->setRequestTimeout($responseTimeout);
        }
        $client = $this->initClient();
        if ($this->isPost) {
            if (!empty($this->clientPostFiles)) {
                foreach ($this->clientPostFiles as $clientPostFile) {
                    $client->addFile(...$clientPostFile->toArray());
                }
            }
            $client->post($requestUri, $this->clientPostData);
        } else {
            $client->get($requestUri);
        }
        $response = new CoResponse($client);
        $client->close();
        return $response;
    }

    /**
     * 初始化请求客户端
     * @return Client
     * @throws InvalidUrl
     */
    private function initClient(): Client
    {
        if ($this->url instanceof CoUrlParts) {
            $port = $this->url->getPort();
            $isSsl = $this->url->getScheme() === 'https';
            if (empty($port)) {
                $port = $isSsl ? 443 : 80;
            }
            $client = new Client($this->url->getHost(), $port, $isSsl);
            $client->set($this->clientSetting);
            $client->setHeaders($this->headers);
            if (!$this->cookies->isEmpty()) {
                $client->setCookies($this->cookies->formatForArray());
            }
            return $client;
        } else {
            throw  new InvalidUrl('url is empty');
        }
    }
}
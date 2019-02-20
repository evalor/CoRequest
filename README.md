Swoole Coroutine Http Client
============

随手封装的不那么自信的HTTP请求库，基于Swoole协程客户端，需要PHP版本7.1+，以及Swoole拓展

## 使用方法

```php
<?php
/**
 * Created by PhpStorm.
 * User: eValor
 * Date: 2019-02-21
 * Time: 03:51
 */

namespace evalor\coRequests;

use evalor\coRequests\Cookie\CoCookieJar;
use evalor\coRequests\Proxy\CoHttpProxy;
use evalor\coRequests\Proxy\CoSocks5Proxy;
use Swoole\Coroutine;

require_once 'vendor/autoload.php';

Coroutine::create(function () {

    $api = 'http://ip.taobao.com/service/getIpInfo.php?ip=116.8.43.185';

    // 可以设置请求代理 Socks5/HTTP
    $httpProxy = new CoHttpProxy('127.0.0.1', 1085);
    $socks5Proxy = new CoSocks5Proxy('127.0.0.1', 1080);

    // 模拟登陆 Response 也会返回 CoCookieJar
    $cookieJar = new CoCookieJar;
    $cookieJar->addCookieStr('H_PS_PSSID=1426_21117_26350_28415_22159; path=/; domain=.baidu.com'); // 字符串风格
    $cookieJar->addCookie('BDSVRTM', 93, 0, '/', '.baidu.com'); // 参数风格

    // 文件上传
    $coPostFile = new CoPostFile;
    $coPostFile->setName('image');
    $coPostFile->setPath('..........');

    $request = new CoRequests($api);
    $coResponse = $request->setProxy($httpProxy)->setProxy($socks5Proxy) // 可以链式操作
        // 请求头设置
        ->setHeader('x-request-with', 'XmlHttpRequest')
        ->setHeaders(['auth-token' => '123456', 'name' => 'value'])
        // 快捷Cookie设置
        ->setCookie('BDSVRTM', 93)
        ->setCookieJar($cookieJar)
        // 支付接口可以证书设置
        ->setSslKeyFile('.........')
        ->setSslCertFile('..........')
        // POST数据和文件上传
        ->setPostData(['name' => 'value'])
        ->setPostFile($coPostFile)
        ->exec();

    $coResponse->hasError(); // 是否请求异常
    $coResponse->getStatusCode(); // 状态码
    $coResponse->getHeaders(); // 全部的header
    $coResponse->getHeader('xxx'); // 获取某个
    $coResponse->parserJson(); // 快速解析JSON响应
    $coResponse->parserXml();  // 快速解析XML响应
    $coResponse->getContent(); // 获取原始响应内容

    // 可以获取到本次请求结束服务器端Set的Cookie 用于下次请求
    $cookieJar = $coResponse->getCookies();
    $request = new CoRequests($api);
    $request->setCookieJar($cookieJar); // 登陆后的状态携带到下个请求

});
```
# mitake-php

[![Continuous Integration](https://github.com/minchao/mitake-php/actions/workflows/continuous-integration.yml/badge.svg?branch=master)](https://github.com/minchao/mitake-php/actions/workflows/continuous-integration.yml)
[![Build status](https://ci.appveyor.com/api/projects/status/sg0uce4i30p5dxf2/branch/master?svg=true&passingText=Windows%20-%20OK)](https://ci.appveyor.com/project/minchao/mitake-php/branch/master)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/1b7b6ee48e884e6aa48d76605871ba83)](https://www.codacy.com/app/minchao/mitake-php?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=minchao/mitake-php&amp;utm_campaign=Badge_Grade)
[![codecov](https://codecov.io/gh/minchao/mitake-php/branch/master/graph/badge.svg)](https://codecov.io/gh/minchao/mitake-php)
[![Latest Stable Version](https://poser.pugx.org/minchao/mitake-php/v/stable)](https://packagist.org/packages/minchao/mitake-php)
[![Latest Unstable Version](https://poser.pugx.org/minchao/mitake-php/v/unstable)](https://packagist.org/packages/minchao/mitake-php)
[![composer.lock](https://poser.pugx.org/minchao/mitake-php/composerlock)](https://packagist.org/packages/minchao/mitake-php)

mitake-php 是[三竹簡訊](https://sms.mitake.com.tw/) SMS HTTP API 的非官方 PHP Client SDK（僅支援台灣行動電話號碼），使用這個 SDK 前，
請確認您已申請三竹簡訊 HTTP Function Call 功能，若您想在 Laravel 下使用，請參考 [mitake-laravel](https://github.com/minchao/mitake-laravel) 提供的 Service provider

## 執行環境

* PHP >= 5.6
* [Guzzle requirements](http://guzzle.readthedocs.io/en/latest/overview.html#requirements)

## 安裝

推薦使用 [Composer](https://getcomposer.org/) 安裝 mitake-php

```
$ composer require minchao/mitake-php
```

## 使用

### Mitake client

實例化 Mitake client，USERNAME 與 PASSWORD 請分別填入您申請的三竹簡訊帳號與密碼

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

$client = new \Mitake\Client('USERNAME', 'PASSWORD');
```

## 範例

呼叫 Mitake API

### 查詢帳戶餘額

查詢目前的帳戶剩餘點數

```php
$resp = $client->queryAccountPoint();
```

### 發送單筆簡訊

發送簡訊前，請先透過 Message 類別建立簡訊內容，再呼叫 API 的 send() 方法發送

```php
$message = (new \Mitake\Message\Message())
    ->setDstaddr('0987654321')
    ->setSmbody('Hello, 世界');
$resp = $client->send($message);
```

### 發送多筆簡訊

若要一次發送多筆簡訊，請先建立欲發送的 **Message objects** 陣列，再呼叫 API 的 sendBatch() 方法發送

```php
$resp = $client->sendBatch([$message1, $message2]);
```

### 發送單筆長簡訊

發送長簡訊前，請先透過 LongMessage 類別建立簡訊內容，再呼叫 API 的 sendLongMessage() 方法發送

```php
$message = (new \Mitake\Message\LongMessage())
    ->setDstaddr('0987654321')
    ->setSmbody('Hello, 世界');
$resp = $client->sendLongMessage($message);
```

### 發送多筆長簡訊

若要一次發送多筆長簡訊，請先建立欲發送的 **LongMessage objects** 陣列，再呼叫 API 的 sendLongMessageBatch() 方法發送

```php
$resp = $client->sendLongMessageBatch([$message1, $message2]);
```

### 查詢簡訊發送狀態

查詢時請帶入簡訊發送後返回的 msgid

```php
$resp = $client->queryMessageStatus(['MESSAGE_ID1', 'MESSAGE_ID2]);
```

### 取消預約發送簡訊

取消時請帶入簡訊 msgid

```php
$resp = $client->cancelMessageStatus(['MESSAGE_ID1', 'MESSAGE_ID2]);
```

### 使用 webhook 接收傳送狀態

發送簡訊時若有設定 response 網址，簡訊伺服器就會在發送狀態更新時以 HTTP GET 方法通知指定的 response 網址，
您可參考 [webhook](webhook/index.php) 中的範例來接收簡訊傳送狀態

簡訊設定 response 網址：

```php
$message->setResponse('https://your.domain.name/callback');
```

建立 webhook：

```php
$app = new Slim\App();

$app->get('/callback', function (Request $request, Response $response, $args) {
    $params = parse_query($request->getUri()->getQuery());
    if (isset($params['msgid'])) {
        $receipt = new Receipt();
        $receipt->setMsgid($params['msgid']);
        // ...
    }
}
```

## 開發

### 開發工具

本專案提供 Command Line Developer Tools，供您在開發時作為測試工具使用

指令：

```
$ bin/mitake
Developer Tools

Usage:
  command [options] [arguments]

Options:
  -h, --help                       Display this help message
  -q, --quiet                      Do not output any message
  -V, --version                    Display this application version
      --ansi                       Force ANSI output
      --no-ansi                    Disable ANSI output
  -n, --no-interaction             Do not ask any interactive question
  -u, --username[=USERNAME]        Mitake Username
  -p, --password[=PASSWORD]        Mitake Password
  -c, --credentials[=CREDENTIALS]  Mitake Credentials
  -v|vv|vvv, --verbose             Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Available commands:
  balance  Retrieve your account balance
  help     Displays help for a command
  list     Lists commands
  send     Send an message
  status   Fetch the status of specific messages
```

使用範例如下：

```
$ bin/mitake send -u USERNAME -p PASSWORD -d 0987654321 -b 'Hello, 世界'
{
    "results": [
        {
            "msgid": "1234567890",
            "statuscode": "1"
        }
    ],
    "accountPoint": "999"
}
```

### 使用 Docker Compose 啟動 webhook 服務

請設定環境變數 VIRTUAL_HOST、LETSENCRYPT_HOST 與 LETSENCRYPT_EMAIL，Docker Compose 會在本機的 443 Port 上啟動 webhook 服務，
並自動透過 Let's Encrypt 建立 SSL 憑證

Command:

```
$ VIRTUAL_HOST=your.domain.name \
  LETSENCRYPT_HOST=your.domain.name \
  LETSENCRYPT_EMAIL=username@mail.com \
  docker-compose up
```

Logs:

```
webhook | 172.18.0.3 - - [01/Oct/2017:05:17:34 +0000] "GET /callback?msgid=1234567890&dstaddr=0987654321&dlvtime=20171001112328&donetime=20171001112345&statusstr=DELIVRD&statuscode=0&StatusFlag=4 HTTP/2.0" 200 156 "-" "Mozilla/5.0"
```

### 執行測試

執行 phpcs 與 phpunit 測試

```
$ composer run check
```

## FAQ

Q：遇到 `PHP Fatal error: Uncaught GuzzleHttp\Exception\ConnectException: cURL error 35`

A：這是因為 OpenSSL 已不支援 TLS 1.1 以下版本，建議使用[長簡訊方法](https://github.com/minchao/mitake-php#%E7%99%BC%E9%80%81%E5%96%AE%E7%AD%86%E9%95%B7%E7%B0%A1%E8%A8%8A)來傳送簡訊，請參考 Issue [#4](https://github.com/minchao/mitake-php/issues/4) 的說明。

**注意：使用 HTTP 或已棄用的 TLS 協議來傳送簡訊，會將傳輸資料暴露外洩的風險之中。**

## License

See the [LICENSE](LICENSE) file for license rights and limitations (MIT).

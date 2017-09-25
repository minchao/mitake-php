# mitake-php

[![Build Status](https://travis-ci.org/minchao/mitake-php.svg?branch=master)](https://travis-ci.org/minchao/mitake-php)
[![codecov](https://codecov.io/gh/minchao/mitake-php/branch/master/graph/badge.svg)](https://codecov.io/gh/minchao/mitake-php)
[![Latest Stable Version](https://poser.pugx.org/minchao/mitake-php/v/stable)](https://packagist.org/packages/minchao/mitake-php)
[![Latest Unstable Version](https://poser.pugx.org/minchao/mitake-php/v/unstable)](https://packagist.org/packages/minchao/mitake-php)
[![composer.lock](https://poser.pugx.org/minchao/mitake-php/composerlock)](https://packagist.org/packages/minchao/mitake-php)

mitake-php 是[三竹簡訊](https://sms.mitake.com.tw/) SMS HTTP API 的非官方 PHP Client SDK （僅支援台灣行動電話號碼），使用這個 SDK 前，
請確認您已申請三竹簡訊 HTTP Function Call 功能

## 執行環境

* PHP >= 5.6
* [Guzzle requirements](http://guzzle.readthedocs.io/en/latest/overview.html#requirements)

## 安裝

推薦使用 [Composer](https://getcomposer.org/) 安裝 mitake-php library

```
composer require minchao/mitake-php
```

## 使用

### 初始化 Mitake client

使用靜態方法 Client::create() 建立 Mitake client，USERNAME 與 PASSWORD 請分別填入您申請的三竹簡訊帳號與密碼

```php
<?php

require_once(__DIR__ . '/vendor/autoload.php');

$client = \Mitake\Client::create('USERNAME', 'PASSWORD', new \GuzzleHttp\Client());
```

## 範例

呼叫 Mitake API

### 查詢帳戶餘額

查詢目前的帳戶剩餘點數

```php
$resp = $client->getAPI()->queryAccountPoint();
```

### 發送單筆簡訊

發送簡訊前，請先透過 Message 類別建立簡訊內容，再呼叫 API 的 send() 方法發送

```php
$message = (new \Mitake\Message\Message())
    ->setDstaddr('0987654321')
    ->setSmbody('Hello, 世界');
$resp = $client->getAPI()->send($message);
```

### 發送多筆簡訊

若要發送多筆簡訊，只需要在呼叫 API 的 sendBatch() 方法時，帶入欲發送的 **Message objects** 陣列

```php
$resp = $client->getAPI()->sendBatch([$message1, $message2]);
```

### 查詢簡訊發送狀態

查詢時請帶入簡訊發送後返回的 msgid

```php
$resp = $client->getAPI()->queryMessageStatus(['MESSAGE_ID1', 'MESSAGE_ID2]);
```

## 測試

執行 phpcs 與 phpunit 測試

```
composer run-script test
```

## License

See the [LICENSE](LICENSE) file for license rights and limitations (MIT).

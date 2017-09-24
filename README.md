# mitake-php

[![Build Status](https://travis-ci.org/minchao/mitake-php.svg?branch=master)](https://travis-ci.org/minchao/mitake-php)

mitake-php 是[三竹簡訊](https://sms.mitake.com.tw/) HTTPS API 的非官方 PHP SDK （僅支援台灣行動電話號碼）

## 執行環境

* PHP >= 5.6

## 安裝

推薦使用 [Composer](https://getcomposer.org/) 安裝 mitake-php

```
composer require minchao/mitake-php
```

## 使用

## 初始化 Mitake Client

USERNAME 與 PASSWORD 請分別填入您申請的三竹簡訊帳號與密碼

```php
<?php

require_once(__DIR__ . '/vendor/autoload.php');

$client = \Mitake\Client::create('USERNAME', 'PASSWORD', new \GuzzleHttp\Client());
```

### 呼叫 API

查詢帳戶餘額

```php
$resp = $client->getAPI()->queryAccountPoint();
```

發送單筆簡訊

```php
$message = new \Mitake\Message\Message();
$message
    ->setDstaddr('0987654321')
    ->setSmbody('Hello, 世界');
$resp = $client->getAPI()->send($message);
```

發送多則簡訊

```php
$resp = $client->getAPI()->send([$message1, $message2]);
```

查詢簡訊發送狀態

```php
$resp = $client->getAPI()->queryMessageStatus(['MESSAGE_ID1', 'MESSAGE_ID2]);
```

## License

See the [LICENSE](LICENSE) file for license rights and limitations (MIT).


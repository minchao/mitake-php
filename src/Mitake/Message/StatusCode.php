<?php

namespace Mitake\Message;

use Mitake\Exception\InvalidArgumentException;

/**
 * Class StatusCode
 * @package Mitake
 */
class StatusCode
{
    const SERVICE_ERROR = '*';
    const SMS_TEMPORARILY_UNAVAILABLE = 'a';
    const SMS_TEMPORARILY_UNAVAILABLE_B = 'b';
    const USERNAME_REQUIRED = 'c';
    const PASSWORD_REQUIRED = 'd';
    const USERNAME_OR_PASSWORD_ERROR = 'e';
    const ACCOUNT_EXPIRED = 'f';
    const ACCOUNT_DISABLED = 'h';
    const INVALID_CONNECTION_ADDRESS = 'k';
    const CHANGE_PASSWORD_REQUIRED = 'm';
    const PASSWORD_EXPIRED = 'n';
    const PERMISSION_DENIED = 'p';
    const SERVICE_TEMPORARILY_UNAVAILABLE = 'r';
    const ACCOUNTING_FAILURE = 's';
    const SMS_EXPIRED = 't';
    const SMS_BODY_EMPTY = 'u';
    const INVALID_PHONE_NUMBER = 'v';
    const RESERVATION_FOR_DELIVERY = '0';
    const CARRIER_ACCEPTED = '1';
    const CARRIER_ACCEPTED_2 = '2';
    const CARRIER_ACCEPTED_3 = '3';
    const DELIVERED = '4';
    const CONTENT_ERROR = '5';
    const PHONE_NUMBER_ERROR = '6';
    const SMS_DISABLE = '7';
    const DELIVERY_TIMEOUT = '8';
    const RESERVATION_CANCELED = '9';

    protected static $messages = [
        self::SERVICE_ERROR => '系統發生錯誤，請聯絡三竹資訊窗口人員',
        self::SMS_TEMPORARILY_UNAVAILABLE => '簡訊發送功能暫時停止服務，請稍候再試',
        self::SMS_TEMPORARILY_UNAVAILABLE_B => '簡訊發送功能暫時停止服務，請稍候再試',
        self::USERNAME_REQUIRED => '請輸入帳號',
        self::PASSWORD_REQUIRED => '請輸入密碼',
        self::USERNAME_OR_PASSWORD_ERROR => '帳號、密碼錯誤',
        self::ACCOUNT_EXPIRED => '帳號已過期',
        self::ACCOUNT_DISABLED => '帳號已被停用',
        self::INVALID_CONNECTION_ADDRESS => '無效的連線位址',
        self::CHANGE_PASSWORD_REQUIRED => '必須變更密碼，在變更密碼前，無法使用簡訊發送服務',
        self::PASSWORD_EXPIRED => '密碼已逾期，在變更密碼前，將無法使用簡訊發送服務',
        self::PERMISSION_DENIED => '沒有權限使用外部Http程式',
        self::SERVICE_TEMPORARILY_UNAVAILABLE => '系統暫停服務，請稍後再試',
        self::ACCOUNTING_FAILURE => '帳務處理失敗，無法發送簡訊',
        self::SMS_EXPIRED => '簡訊已過期',
        self::SMS_BODY_EMPTY => '簡訊內容不得為空白',
        self::INVALID_PHONE_NUMBER => '無效的手機號碼',
        self::RESERVATION_FOR_DELIVERY => '預約傳送中',
        self::CARRIER_ACCEPTED => '已送達業者',
        self::CARRIER_ACCEPTED_2 => '已送達業者',
        self::CARRIER_ACCEPTED_3 => '已送達業者',
        self::DELIVERED => '已送達手機',
        self::CONTENT_ERROR => '內容有錯誤',
        self::PHONE_NUMBER_ERROR => '門號有錯誤',
        self::SMS_DISABLE => '簡訊已停用',
        self::DELIVERY_TIMEOUT => '逾時無送達',
        self::RESERVATION_CANCELED => '預約已取消',
    ];

    /**
     * @var string
     */
    protected $code;

    /**
     * StatusCode constructor.
     * @param $code string
     * @throws InvalidArgumentException
     */
    public function __construct($code)
    {
        if (!array_key_exists($code, self::$messages)) {
            throw new InvalidArgumentException(sprintf('unexpected message status code: %s', $code));
        }

        $this->code = $code;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function message()
    {
        return self::$messages[$this->code];
    }
}

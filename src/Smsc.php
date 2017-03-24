<?php

namespace ejen\smsc;

use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class Smsc extends \yii\base\Component
{
    public $login;
    public $password;

    public $baseUrl = 'https://smsc.ru/sys/';

    public $charset = 'utf-8';
    public $fmt = 3;

    public function status($numbers, $ids, $params = [])
    {
        return $this->apiCall(
            'send.php',
            ArrayHelper::merge(
                $this->commonParams,
                [
                    'phone' => implode(',', (array)$numbers),
                    'id' => implode(',', (array)$ids),
                    'charset' => $this->charset,
                ],
                $params
            )
        );
    }

    public function send($numbers, $message, $params = [])
    {
        return $this->apiCall(
            'send.php',
            ArrayHelper::merge(
                $this->commonParams,
                [
                    'phones' => implode(';', (array)$numbers),
                    'mes' => $message,
                    'charset' => $this->charset,
                    'cost' => 3,
                ],
                $params
            )
        );
    }

    public function balance($params = [])
    {
        return $this->apiCall(
            'balance.php',
            ArrayHelper::merge(
                $this->commonParams,
                $params
            )
        );
    }

    public function getCommonParams()
    {
        return [
            'login' => $this->login,
            'psw' => md5($this->password),
            'fmt' => $this->fmt,
        ];
    }

    public function apiCall($method, $params)
    {        
        $response = file_get_contents($this->baseUrl.$method.'?'.http_build_query($params));
        switch($this->fmt)
        {
            case 3: // json
                return Json::decode($response);
            default: // raw
                return $response;
        }
    }
}

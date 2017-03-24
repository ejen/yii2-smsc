<?php

namespace ejen\smsc;

use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class Smsc extends \yii\base\Component
{
    public $login;
    public $password;

    public $baseUrl = 'https://smsc.ru/sys/';

    public function send($numbers, $message, $params = [])
    {
        $phones = $numbers;
        if (is_array($numbers))
        {
            $phones = implode(";", $numbers);
        }

        $params = ArrayHelper::merge(
            [
                'login' => $this->login,
                'psw' => md5($this->password),
                'phones' => $phones,
                'mes' => $message,
                'charset' => 'utf-8',
                'fmt' => 3, // json
                'cost' => 3,
            ],
            $params
        );

        $response = file_get_contents($this->baseUrl.'send.php?'.http_build_query($params));
        return Json::decode($response);
    }

    public function getBalance($params = [])
    {
        $params = ArrayHelper::merge(
            [
                'login' => $this->login,
                'psw' => md5($this->password),
                'fmt' => 3,
            ],
            $params
        );

        $response = file_get_contents($this->baseUrl.'balance.php?'.http_build_query($params));
        return Json::decode($response);
    }
}

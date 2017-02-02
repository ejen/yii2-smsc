<?php

namespace ejen\smsc;

class Smsc extends \yii\base\Component
{
    public $login;
    public $password;

    public function send($numbers, $message)
    {
        $phones = $numbers;
        if (is_array($numbers))
        {
            $phones = implode(";", $numbers);
        }

        $url = 'http://smsc.ru/sys/send.php?'.http_build_query([
            'login' => $this->login,
            'psw' => md5($this->password),
            'phones' => $phones,
            'mes' => $message,
            'charset' => 'utf-8',
            'fmt' => 3, // json
            'cost' => 3, 
        ]);

        $response = file_get_contents($url);
        return json_decode($response);
    }

    public function getBalance()
    {
        $url = 'http://smsc.ru/sys/balance.php?'.http_build_query([
            'login' => $this->login,
            'psw' => md5($this->password),
            'fmt' => 3,
        ]);

        $response = file_get_contents($url);
        return json_decode($response);
    }
}

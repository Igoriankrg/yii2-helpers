<?php

namespace yii2lab\helpers;

class ClientHelper
{

    /**
     * Получить IP адрес клиента
     * @return string
     */
    public static function ip() {
        if (APP == CONSOLE) {
            return '127.0.0.1';
        }

        if ($_SERVER['REMOTE_ADDR'] == env('servers.nat.address') && isset($_SERVER['HTTP_CLIENT_IP'])) {
            $clientIp = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            $clientIp = $_SERVER['REMOTE_ADDR'];
        }
        return $clientIp;
    }
	
}

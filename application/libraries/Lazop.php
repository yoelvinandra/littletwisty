<?php
require_once(APPPATH . 'third_party/lazop/LazopSdk.php');

class Lazop {

    private $client;

    public function __construct($config = []) {
        $appKey    = $config['appKey'] ?? 'YOUR_APP_KEY';
        $appSecret = $config['appSecret'] ?? 'YOUR_APP_SECRET';
        $baseurllazada   = $config['baseurllazada'] ?? 'https://api.lazada.co.id/rest';

        $this->client = new LazopClient($baseurllazada, $appKey, $appSecret);
    }

    public function getClient() {
        return $this->client;
    }
}

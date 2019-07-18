<?php

namespace App\Helpers;

use Curl;

class Server1C
{
    private $curl;
    private $api_param = 'params';

    public function __construct()
    {
        $username = 'admin';
        $password = 1252351;
        $this->curl = new Curl('http://95.213.156.3:8888/');
        $this->curl->config_load('trip.cfg');
        $this->curl->set(CURLOPT_USERPWD, $username.':'.$password);
    }

    public function request($request)
    {
        return $this->curl->request($request);
    }

    public function post($post)
    {
        $this->curl->clear_headers();
        $this->curl->add_header('Content-Type: application/json');
        $postData = json_encode($post, JSON_UNESCAPED_UNICODE);

        return $this->curl->post($postData, false);
    }
}

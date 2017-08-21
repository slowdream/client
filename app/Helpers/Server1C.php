<?php

namespace App\Helpers;
use Curl;

class Server1C{

	private $curl;

	public function __construct() 
	{
		$username = 'admin';
        $password = 1252351;
        $this->curl = new Curl('http://95.213.156.3:8888/');
        $this->curl->config_load('trip.cfg');
        $this->curl->set(CURLOPT_USERPWD, $username . ":" . $password);
	}

	public function request($request)
	{
		return $this->curl->request($request);
	}	
	public function post($post)
	{
		return $this->curl->post($post);
	}
}
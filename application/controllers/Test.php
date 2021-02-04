<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once "Secure_area.php";
class Test extends Secure_area {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
	}
	public function index()
	{
		$indicesServer = array('PHP_SELF', 
		'argv', 
		'argc', 
		'GATEWAY_INTERFACE', 
		'SERVER_ADDR', 
		'SERVER_NAME', 
		'SERVER_SOFTWARE', 
		'SERVER_PROTOCOL', 
		'REQUEST_METHOD', 
		'REQUEST_TIME', 
		'REQUEST_TIME_FLOAT', 
		'QUERY_STRING', 
		'DOCUMENT_ROOT', 
		'HTTP_ACCEPT', 
		'HTTP_ACCEPT_CHARSET', 
		'HTTP_ACCEPT_ENCODING', 
		'HTTP_ACCEPT_LANGUAGE', 
		'HTTP_CONNECTION', 
		'HTTP_HOST', 
		'HTTP_REFERER', 
		'HTTP_USER_AGENT', 
		'HTTPS', 
		'REMOTE_ADDR', 
		'REMOTE_HOST', 
		'REMOTE_PORT', 
		'REMOTE_USER', 
		'REDIRECT_REMOTE_USER', 
		'SCRIPT_FILENAME', 
		'SERVER_ADMIN', 
		'SERVER_PORT', 
		'SERVER_SIGNATURE', 
		'PATH_TRANSLATED', 
		'SCRIPT_NAME', 
		'REQUEST_URI', 
		'PHP_AUTH_DIGEST', 
		'PHP_AUTH_USER', 
		'PHP_AUTH_PW', 
		'AUTH_TYPE', 
		'PATH_INFO', 
		'ORIG_PATH_INFO');

		$indicesServer[] = 'XXX';

		echo "<pre>";
		print_r ($indicesServer);
		echo "</pre>";
		exit();

		echo '<table cellpadding="10">' ;
		foreach ($indicesServer as $arg) {
		    if (isset($_SERVER[$arg])) {
		        echo '<tr><td>'.$arg.'</td><td>' . $_SERVER[$arg] . '</td></tr>' ;
		    }
		    else {
		        echo '<tr><td>'.$arg.'</td><td>-</td></tr>' ;
		    }
		}
		echo '</table>' ;
	}

	public function variable($value='')
	{
		$config['base_url'] = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_ENV['FORCE_HTTPS']) && $_ENV['FORCE_HTTPS'] == 'true')) ? 'https' : 'http'
			.'://' . $_SERVER['HTTP_HOST']
			.str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

		echo $config['base_url'];
	}

	public function new_function(){

	echo bcmul('1.34747474747', '35', 3); // 47.161
	echo bcmul('2', '4'); // 8

	}

	public function culrtime($value='')
	{

		//The URL we want to send a HTTP request to.
		//In this case, it is a script on my local machine.
		$url = 'http://e-consulta.sunat.gob.pe/cl-at-ittipcam/tcS01Alias';
		 
		//Initiate cURL
		$ch = curl_init($url);
		 
		//Tell cURL that it should only spend 10 seconds
		//trying to connect to the URL in question.
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		 
		//A given cURL operation should only take
		//30 seconds max.
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		 
		//Tell cURL to return the response output as a string.
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		 
		//Execute the request.
		$response = curl_exec($ch);
		//Did an error occur? If so, dump it out.
		if(curl_errno($ch)){
		    throw new Exception(curl_error($ch));
		}


	}

	public function FunctionName($value='')
	{
		$url = "http://mail.yahoo.com";//ruta
		$ch = curl_init($url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_TIMEOUT,5);
		$output = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if ($httpcode==200) {
			//all script ok
		}else{
			//script bad return false
		}
		exit;
	}

}


/* End of file Test.php */
/* Location: ./application/controllers/Test.php */
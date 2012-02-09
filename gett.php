<?php

/**
 *	ge.tt PHP Library
 * 	Easily interact with ge.tt API via PHP.
 *
 * 	Version: 	1.0.0
 * 
 * 	Author:		Ahmed Hosny <admin@spanlayer.com>
 * 	Website:	http://www.spanlayer.com
 * 
 * 	License:	This library is Free for everyone to use or modify,
 * 				Keeping the original credits,
 * 				Appending notes and credits for any modifications.
 */

class gett {
	
	private $api_key = '';	// Change this to your API Key
	private $c = false;
	private $access_token = false;
	
	public function __construct() {
		session_start();
	}
	
	public function request($method, $post_data=Array()) {

		$this->c = curl_init();
		curl_setopt($this->c, CURLOPT_RETURNTRANSFER, true);
		
		$post_data_str = json_encode($post_data);
		$this->access_token = $this->access_token ? $this->access_token : (isset($_SESSION['gett_accesstoken']) ? $_SESSION['gett_accesstoken'] : false);

		curl_setopt($this->c, CURLOPT_URL, 'https://open.ge.tt/1/' . $method . ($this->access_token ? "?accesstoken={$this->access_token}" : ''));
		
		if(is_array($post_data) && count($post_data) > 0)
		{
			curl_setopt($this->c, CURLOPT_POST, 1);
			curl_setopt($this->c, CURLOPT_POSTFIELDS, $post_data_str);
		}
		
		$result = json_decode(curl_exec($this->c));
		
		if(isset($result->error))
			trigger_error('<b>ge.tt said: <u>' . $result->error . '</u></b>', E_USER_ERROR);
		elseif(is_null($result))
			trigger_error("<b><u>Unknown method ($method) or ge.tt API is down !</u></b>", E_USER_ERROR);
		else	
			return $result;
		
	}
	
	public function auth($email, $password) {
		if(isset($_SESSION['gett_accesstoken']) OR $this->access_token)
			return false;
			
		$user = $this->request('users/login',
					Array('apikey'=>$this->api_key, 'email'=>$email, 'password'=>$password)
		);
		
		$_SESSION['gett_accesstoken'] = $user->accesstoken;
		$this->access_token = $user->accesstoken;
	}
}

?>

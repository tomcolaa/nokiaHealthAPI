<?php

	if(isset($_GET["code"])) {
		
		//Code
		$code = $_GET["code"];
		//ResponseType must be 'code'
		$grantType = "authorization_code";
		//Redirect URL after Authorization
		$redirectURL = "";
		//ClientID from your App
		$clientId = "";
		//ConsumerSecret from your App
		$clientSecret = "";
		//URL
		$url = 'https://account.health.nokia.com/oauth2/token';
		
		//Post Request 1
		$data = array('grant_type' => $grantType, 'client_id' => $clientId, 'client_secret' => $clientSecret, 'code' => $code, 'redirect_uri' => $redirectURL);
		// use key 'http' even if you send the request to https://...
		$options = array(
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($data)
			)
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		if ($result === FALSE) { /* Handle error */ }
		
		$finalArray = array();
		$asArr = explode( ',', $result );
		foreach( $asArr as $val ){
		  $tmp = explode( ':', $val );
		  array_push($finalArray, $tmp[1]);
		}

		//Access Token
		$accessToken = str_replace('"', "",$finalArray[0]);
		$url = "https://api.health.nokia.com/user";
		$data = array('action' => 'getinfo', 'access_token' => $accessToken);
		// use key 'http' even if you send the request to https://...
		$options = array(
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($data)
			)
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		if ($result === FALSE) { /* Handle error */ }
		
		print_r($result);
		
	}
	
?>
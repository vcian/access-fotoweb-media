<?php
if(!isset($_SESSION)){session_start();}
if(isset($_SESSION['fw_url'])){
	$_SESSION['fw_url'] = filter_var($_SESSION['fw_url'], FILTER_SANITIZE_URL);
    $fotoweb_url = $_SESSION['fw_url'];
}
if(isset($_SESSION['access_token'])){
	$_SESSION['access_token'] = filter_var($_SESSION['access_token'], FILTER_SANITIZE_STRING);
    $access_token = $_SESSION['access_token'];
}
if(isset($_SESSION['client_id'])){
	$_SESSION['client_id'] = filter_var($_SESSION['client_id'], FILTER_SANITIZE_STRING);
    $client_id = $_SESSION['client_id'];
}
if(isset($_SESSION['client_secret'])){
	$_SESSION['client_id'] = filter_var($_SESSION['client_secret'], FILTER_SANITIZE_STRING);
    $client_secret = $_SESSION['client_secret'];
}
if(isset($_SESSION['redirect_uri'])){
	$_SESSION['redirect_uri'] = filter_var($_SESSION['redirect_uri'], FILTER_SANITIZE_URL);
    $redirect_uri = $_SESSION['redirect_uri'];
}
if(isset($_GET['code'])){
	$_GET['code'] = filter_var($_GET['code'], FILTER_SANITIZE_STRING);
    $authorization_code = $_GET['code'];
}
if(isset($_SESSION['state'])){
	$_SESSION['state'] = filter_var($_SESSION['state'], FILTER_SANITIZE_URL);
    $session_state = $_SESSION['state'];
}
if(isset($_GET['state'])){
	$_GET['state'] = filter_var($_GET['state'], FILTER_SANITIZE_STRING);
    $state = $_GET['state'];
}
function fw_media_get_access_token_from_refresh_token(){
    $refresh_token = $_SESSION['refresh_token'];
    global $access_token;
    global $client_id;
    global $client_secret;
    global $fw_url;

    $url = "$fw_url/fotoweb/oauth2/token";

    //Refresh the token
    $data = array(
        'grant_type' => 'refresh_token',
        'refresh_token' => $refresh_token,
        'client_id' => $client_id,
        'client_secret' => $client_secret,
     );
    
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );
    $context  = stream_context_create($options);
    $refresh_result = file_get_contents($url, false, $context);

    if($refresh_result){
        $_SESSION['access_token'] = json_decode($refresh_result)->access_token;
        $_SESSION['refresh_token'] = json_decode($refresh_result)->refresh_token;
    }

    return json_decode($refresh_result)->access_token;
}
<?php
if(!isset($_SESSION)){session_start();}
if(isset($_SESSION['fw_url'])){
	$_SESSION['fw_url'] = filter_var($_SESSION['fw_url'], FILTER_SANITIZE_URL);
    $fw_url = $_SESSION['fw_url'];
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
if(!isset($_SESSION['access_token'])){
 fw_media_get_access_token();
}

function fw_media_get_access_token(){
    global $client_id;
    global $client_secret;
    global $redirect_uri;
    global $authorization_code;
    global $fw_url;
    global $session_state;
    global $state;
    
    if(!$authorization_code or ($state == null)){
         return;
    }
    
    $url = "$fw_url/fotoweb/oauth2/token";
    
    $data = array(
        'grant_type' => 'authorization_code',
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'code' => $authorization_code,
        'redirect_uri' => $redirect_uri
     );
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' =>  http_build_query($data)
        )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    $access_token = json_decode($result)->access_token;
    $_SESSION['access_token'] = $access_token;

    $refresh_token = json_decode($result)->refresh_token;
    $_SESSION['refresh_token'] = $refresh_token;

    if ($_SESSION['access_token']){
        echo 'You have now been successfully logged in. Please close this tab and return to your WordPress site.';
        ?>
        <script> window.localStorage.setItem('isLoggedIn', 'true');</script> 
        <?php
    } 
}
?>

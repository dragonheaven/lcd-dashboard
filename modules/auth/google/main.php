<?php
require '../../../engine.php';

$client = new Google_Client();
$client->setAuthConfig('../../../google_oauth/'.$product['id'].'.json');
$client->addScope("email");
$client->addScope("profile");

if(isset($_SESSION['access_token']) && $_SESSION['access_token']) {
$client->setAccessToken($_SESSION['access_token']);

if($client->isAccessTokenExpired()){unset($_SESSION['access_token']);header('location: https://'.$_SERVER['HTTP_HOST'].'/auth/google/');exit;}

$service = new Google_Service_Oauth2($client);
$user = $service->userinfo->get();

$_SESSION['google_user_data']= (array) $user;
header('location: https://'.$_SERVER['HTTP_HOST'].'/auth/google/authorize/');
}else{
header('location: https://'.$_SERVER['HTTP_HOST'].'/auth/google/callback/');
}
?>
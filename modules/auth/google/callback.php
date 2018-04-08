<?php
require '../../../engine.php';

$client = new Google_Client();
$client->setAuthConfig('../../../google_oauth/'.$product['id'].'.json');
$client->setRedirectUri('https://'.$_SERVER['HTTP_HOST'].'/auth/google/callback/');
$client->addScope("email");
$client->addScope("profile");

if (!isset($_GET['code'])){
$auth_url = $client->createAuthUrl();
header('location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
}else{
$client->authenticate($_GET['code']);
$_SESSION['access_token'] = $client->getAccessToken();
header('location: https://'.$_SERVER['HTTP_HOST'].'/auth/google/');
}
?>
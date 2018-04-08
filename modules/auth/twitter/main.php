<?php
require '../../../engine.php';
use Abraham\TwitterOAuth\TwitterOAuth;

if (!isset($_SESSION['twitter_access_token'])) {
$connection = new TwitterOAuth($product['twitter_consumer_key'], $product['twitter_consumer_secret']);
$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => 'https://'.$_SERVER['HTTP_HOST'].'/auth/twitter/callback/'));
$_SESSION['twitter_oauth_token'] = $request_token['oauth_token'];
$_SESSION['twitter_oauth_token_secret'] = $request_token['oauth_token_secret'];
header('location: '.$connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token'])));
}else{
$access_token = $_SESSION['twitter_access_token'];
$connection = new TwitterOAuth($product['twitter_consumer_key'], $product['twitter_consumer_secret'], $access_token['oauth_token'], $access_token['oauth_token_secret']);
$params = array('include_email' => 'true', 'include_entities' => 'false', 'skip_status' => 'true');
$user = $connection->get("account/verify_credentials",$params);
$_SESSION['twitter_user_data']= (array) $user;
header('location: https://'.$_SERVER['HTTP_HOST'].'/auth/twitter/authorize/');
}
?>
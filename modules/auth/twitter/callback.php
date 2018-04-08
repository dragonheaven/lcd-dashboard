<?php
require '../../../engine.php';
use Abraham\TwitterOAuth\TwitterOAuth;

if (isset($_REQUEST['oauth_verifier'], $_REQUEST['oauth_token']) && $_REQUEST['oauth_token'] == $_SESSION['twitter_oauth_token']){
$request_token = [];
$request_token['oauth_token'] = $_SESSION['twitter_oauth_token'];
$request_token['oauth_token_secret'] = $_SESSION['twitter_oauth_token_secret'];
$connection = new TwitterOAuth($product['twitter_consumer_key'], $product['twitter_consumer_secret'], $request_token['oauth_token'], $request_token['oauth_token_secret']);
$access_token = $connection->oauth("oauth/access_token", array("oauth_verifier" => $_REQUEST['oauth_verifier']));
$_SESSION['twitter_access_token'] = $access_token;
header('location: https://'.$_SERVER['HTTP_HOST'].'/auth/twitter/');exit;
}

header('location: https://'.$_SERVER['HTTP_HOST'].'/signin/');
?>
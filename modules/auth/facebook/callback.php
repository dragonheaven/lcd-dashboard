<?php
require '../../../engine.php';

$fb = new Facebook\Facebook(['app_id' => $product['facebook_app_id'],'app_secret' => $product['facebook_app_secret'],'default_graph_version' => 'v2.1']);
$helper = $fb->getRedirectLoginHelper();

try {
$accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {header('location: https://'.$_SERVER['HTTP_HOST'].'/auth/facebook/');exit;} catch(Facebook\Exceptions\FacebookSDKException $e) {header('location: https://'.$_SERVER['HTTP_HOST'].'/auth/facebook/');exit;}

try {
$response = $fb->get('/me?fields=id,name,first_name,last_name,birthday,website,gender,location,email', $accessToken);
} catch(Facebook\Exceptions\FacebookResponseException $e) {header('location: https://'.$_SERVER['HTTP_HOST'].'/auth/facebook/');exit;} catch(Facebook\Exceptions\FacebookSDKException $e) {header('location: https://'.$_SERVER['HTTP_HOST'].'/auth/facebook/');exit;}

$user = $response->getGraphUser();

$_SESSION['facebook_user_data']= (array) $user;
foreach($_SESSION['facebook_user_data'] as $arr){$_SESSION['facebook_user_data']=$arr;}

header('location: https://'.$_SERVER['HTTP_HOST'].'/auth/facebook/authorize/');
?>
<?php
require '../../../engine.php';

$fb = new Facebook\Facebook(['app_id' => $product['facebook_app_id'],'app_secret' => $product['facebook_app_secret'],'default_graph_version' => 'v2.1']);

$helper = $fb->getRedirectLoginHelper();

$permissions=['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl('https://'.$_SERVER['HTTP_HOST'].'/auth/facebook/callback/', $permissions);

header('location: '.($loginUrl));//htmlspecialchars
?>
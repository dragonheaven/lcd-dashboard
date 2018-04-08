<?php
require 'engine.php';
if($logged_in['flag'] && $_SESSION['two_factor']==''){

$available_modules=array('dashboard','buy','profile','support','admin','two_factor');
if(isset($_GET['module']) && in_array($_GET['module'],$available_modules)){$module=$_GET['module'];}else{if($_SERVER['REQUEST_URI']!='/'){header('location: /');exit();}$module='dashboard';}

require 'modules/header_meta.php';require 'modules/portal/'.$module.'.php';exit;

}else{

$available_modules=array('signup','signin','reset');
if(isset($_GET['module']) && in_array($_GET['module'],$available_modules)){$module=$_GET['module'];}else{if($_SERVER['REQUEST_URI']!='/'){header('location: /');exit();}$module='signin';}

require 'modules/auth/form.php';exit;}
?>
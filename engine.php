<?php
// error_reporting(0);
// ini_set('display_errors','0');
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors','1');
session_start();
require 'vendor/autoload.php';

#Отправка email
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

#Coinpayments API
require 'modules/payments/coinpayments.inc.php';
#Получение адреса в нужной криптовалюте (например, BTC) для депозитов на него средств пользователем
function getCoinPaymentsWalletAddress($currency,$public_key,$private_key){
$cps = new CoinPaymentsAPI();
$cps->Setup($public_key,$private_key);
$result = $cps->GetCallbackAddress($currency);
if($result['error']=='ok'){return $result['result']['address'];}else{return '';}}
#END Coinpayments API

#Cloudflare IP и страна
$user['ip'] = (isset($_SERVER["HTTP_CF_CONNECTING_IP"])?$_SERVER["HTTP_CF_CONNECTING_IP"]:$_SERVER['REMOTE_ADDR']);
$user['country_code'] = (isset($_SERVER["HTTP_CF_IPCOUNTRY"])?strtolower($_SERVER["HTTP_CF_IPCOUNTRY"]):'xx');

#Настройки базы
$settings=array(
'db_host'=>'localhost',
'db_user'=>'lucyd_dashboard',
'db_password'=>'razdvatree45X!!!!!',
'db_name'=>'lucyd_dashboard'
);

#Подключение к базе
$db = new PDO('mysql:host='.$settings['db_host'].';dbname='.$settings['db_name'].'',$settings['db_user'],$settings['db_password']);

#Информация о портале на текущем домене и ключи API OAuth
$query = $db->prepare("SELECT * FROM products WHERE domain = ?");
$query->execute(array($_SERVER['HTTP_HOST']));
if ($query->rowCount() == 0) {header('location: https://google.com/');exit;}
$product = $query->fetch(\PDO::FETCH_ASSOC);

#Курсы криптовалют к доллару
$query = $db->prepare("SELECT * FROM currency_settings WHERE product_id = ?");
$query->execute(array($product['id']));
$currency_settings = $query->fetch(\PDO::FETCH_ASSOC);
unset($currency_settings['product_id']);

$query = $db->prepare("SELECT time FROM currency_to_usd WHERE id = ?");
$query->execute(array('1'));

if(time()>($query->fetch(\PDO::FETCH_ASSOC)['time']+300)){
set_time_limit(0);
$prices=array();

$query = $db->prepare("UPDATE currency_to_usd SET time = ? WHERE id = ?");
$query->execute(array_values(array(time(), array('1'))));

$currencies=array();
foreach($currency_settings as $letters=>$enabled){$currencies[''.$letters.'']=$enabled;}//if($enabled!='0'){}

$request=file_get_contents('https://api.icoportals.com/currency/USD/');
if($request!==false){
$response=json_decode($request, true);
if(isset($response['status']) && $response['status']=='ok'){unset($response['status']);
foreach($response as $currency=>$price){$prices[''.$currency.'']=$price;}
}}

if(count($prices)!=count($currencies)){
foreach($currencies as $currency=>$enabled){
$request=file_get_contents('https://api.cryptonator.com/api/full/'.$currency.'-usd');
if($request!==false && isset(json_decode($request, true)['ticker']['price'])){$prices[''.$currency.'']=json_decode($request, true)['ticker']['price'];}
}}

if(count($prices)!=count($currencies)){
foreach($currencies as $currency){
if(!isset($prices[''.$currency.''])){
$request=file_get_contents('https://min-api.cryptocompare.com/data/price?fsym='.$currency.'&tsyms=USD');
if($request!==false && isset(json_decode($request, true)['USD'])){$prices[''.$currency.'']=json_decode($request, true)['USD'];}
}}}


if(is_array($prices) && count($prices)==count($currencies)){
$customParamsQueryArray = Array();

foreach($prices as $paramKey => $paramValue) {$customParamsQueryArray[] = array('value' => $paramKey . ' = ?');}
$setParams = ', ' . implode(', ', array_map(function ($entry) {return $entry['value'];}, $customParamsQueryArray));

$query = $db->prepare("UPDATE currency_to_usd SET time = ? {$setParams} WHERE id = ?");
$query->execute(array_values(array_merge(array(time()), $prices, array('1'))));
}
}

$query = $db->prepare("SELECT * FROM currency_to_usd WHERE id = ?");
$query->execute(array('1'));
$currency_to_usd = $query->fetch(\PDO::FETCH_ASSOC);
#END курсы криптовалют к доллару

#Курсы криптовалют к Ethereum
if($product['token_price_eth']!='0' && $product['token_price_eth']>0){

$query = $db->prepare("SELECT * FROM currency_settings WHERE product_id = ?");
$query->execute(array($product['id']));
$currency_settings = $query->fetch(\PDO::FETCH_ASSOC);
unset($currency_settings['product_id']);

$query = $db->prepare("SELECT time FROM currency_to_eth WHERE id = ?");
$query->execute(array('1'));

if(time()>($query->fetch(\PDO::FETCH_ASSOC)['time']+300)){
set_time_limit(0);
$prices=array();

$query = $db->prepare("UPDATE currency_to_eth SET time = ? WHERE id = ?");
$query->execute(array_values(array(time(), array('1'))));

$currencies=array();
foreach($currency_settings as $letters=>$enabled){$currencies[''.$letters.'']=$enabled;}//if($enabled!='0'){}

$request=file_get_contents('https://api.icoportals.com/currency/ETH/');
if($request!==false){
$response=json_decode($request, true);
if(isset($response['status']) && $response['status']=='ok'){unset($response['status']);
foreach($response as $currency=>$price){$prices[''.$currency.'']=$price;}
}}

if(count($prices)!=count($currencies)){
foreach($currencies as $currency=>$enabled){
if($currency=='ETH'){$prices[''.$currency.'']='1';}else{
$request=file_get_contents('https://api.cryptonator.com/api/full/'.$currency.'-eth');
if($request!==false && isset(json_decode($request, true)['ticker']['price'])){$prices[''.$currency.'']=json_decode($request, true)['ticker']['price'];}
}}}

if(count($prices)!=count($currencies)){
foreach($currencies as $currency){
if(!isset($prices[''.$currency.''])){
if($currency=='ETH'){$prices[''.$currency.'']='1';}else{
$request=file_get_contents('https://min-api.cryptocompare.com/data/price?fsym='.$currency.'&tsyms=ETH');
if($request!==false && isset(json_decode($request, true)['ETH'])){$prices[''.$currency.'']=json_decode($request, true)['ETH'];}
}}}}


if(is_array($prices) && count($prices)==count($currencies)){
$customParamsQueryArray = Array();

foreach($prices as $paramKey => $paramValue) {$customParamsQueryArray[] = array('value' => $paramKey . ' = ?');}
$setParams = ', ' . implode(', ', array_map(function ($entry) {return $entry['value'];}, $customParamsQueryArray));

$query = $db->prepare("UPDATE currency_to_eth SET time = ? {$setParams} WHERE id = ?");
$query->execute(array_values(array_merge(array(time()), $prices, array('1'))));
}
}

$query = $db->prepare("SELECT * FROM currency_to_eth WHERE id = ?");
$query->execute(array('1'));
$currency_to_eth = $query->fetch(\PDO::FETCH_ASSOC);

}
#END курсы криптовалют к Ethereum

#Отслеживание прямых транзакций на смартконтракт и внесение их в базу данных
function convertFromSatoshi($value){return rtrim(rtrim(bcdiv($value,"1000000000000000000",18),0),'.');}

if(time()>($product['ethereum_timestamp']+300) && $mode!='light'){
$eth_raised_smartcontract=0;
$ethereum_last_block=$product['ethereum_last_block'];

$txlist=file_get_contents('http://api.etherscan.io/api?module=account&action=txlist&address='.$product['smartcontract_address'].'&startblock='.($product['ethereum_last_block']+1).'&endblock=99999999&sort=asc&apikey='.$product['etherscan_api_key'].'');
if($txlist!==false){
$array=json_decode($txlist, true);
$eth_raised_smartcontract=0;
if($array['status']!='0'){
foreach($array['result'] as $arr){
if($arr['isError']==0 && $arr['value']>0){

$query = $db->prepare("INSERT INTO payments (wallet_address, transaction_id, amount, amounti, currency, status, system, complete, time, product_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$query->execute(array($arr['from'],$arr['hash'],convertFromSatoshi($arr['value']),$arr['value'],'ETH','100','smartcontract','1',$arr['timeStamp'],$product['id']));

$eth_raised_smartcontract+=convertFromSatoshi($arr['value']);
$ethereum_last_block=end($array['result'])['blockNumber'];
}}

}}

$query = $db->prepare("UPDATE products SET ethereum_last_block = ?, ethereum_timestamp = ?, eth_raised_smartcontract = ? WHERE id = ?");
$query->execute(array_values(array($ethereum_last_block,time(),($product['eth_raised_smartcontract']+$eth_raised_smartcontract),$product['id'])));
}
#END Отслеживание прямых транзакций на смартконтракт

#Язык
#Соответствие стран языкам https://www.infoplease.com/languages-spoken-each-country-world https://docs.oracle.com/cd/E13214_01/wli/docs92/xref/xqisocodes.html
$country_codes=array('ko'=>array('ko'),'cn'=>array('cn','tw'),'ru'=>array('hy','az','be','et','ka','kz','kg','lv','lt','md','ru','tj','tm','uk','uz'),'ja'=>array('ja'));
$locale_codes=array('ko'=>array('ko'),'cn'=>array('zh','th'),'ru'=>array('ru','uk','be','et','lv','lt'),'ja'=>array('ja'));
$available_languages=array('ko','cn','ru','ja','en');

if(!isset($_SESSION['user_language'])){
$i=0;foreach($country_codes as $language_title){if(in_array($user['country_code'],$country_codes[array_keys($country_codes)[$i]])){$_SESSION['user_language']=array_keys($country_codes)[$i];}else{$_SESSION['user_language']='en';}$i++;}unset($i);

#Определение по ip невозможно, проверяем язык браузера
if($_SESSION['user_language']=='en'){
$localePreferences = explode(",",$_SERVER['HTTP_ACCEPT_LANGUAGE']);
if(is_array($localePreferences) && count($localePreferences) > 0) {
$browserLocale=substr($localePreferences[0],0,2);
$i=0;foreach($locale_codes as $language_title){if(in_array($browserLocale,$locale_codes[array_keys($locale_codes)[$i]])){$_SESSION['user_language']=array_keys($locale_codes)[$i];}$i++;}unset($i);}}

}

if(isset($_GET['lng']) && in_array($_GET['lng'],$available_languages)){$_SESSION['user_language']=$_GET['lng'];$location_add='';if(in_array(rtrim(str_replace($product['url'],'',$_SERVER['HTTP_REFERER']), '/'),array('buy','profile','signup','signin','reset','support','admin','two_factor'))){$location_add=rtrim(str_replace($product['url'],'',$_SERVER['HTTP_REFERER']), '/').'/';}header('location: /'.$location_add.'');exit();}

require('language.php');
$lng=${"language_".$_SESSION['user_language'].""};
#end language

#Класс авторизации пользователей
$auth = new PHPAuth\Auth($db,$product,$user,$lng);

#Если пользователь авторизован, получаем информацию о нем
$logged_in=$auth->logged_in();
if($logged_in['flag']){
$user['id']=$logged_in['uid'];

$query = $db->prepare("SELECT * FROM users WHERE id = ?");
$query->execute(array($user['id']));
if($query->rowCount()==0){$auth->signout($_SESSION['authID']);header('location: /');exit;}
$user = $query->fetch(\PDO::FETCH_ASSOC);

if($user['email']=='' && $user['oauth_email']!=''){$user['email']=$user['oauth_email'];}

#Кошельки пользователя
if($user['ethereum_address_provided']=='0'){$user['ethereum_address']='';}
if($user['SBD_address_provided']=='0'){$user['SBD_address']='';}
if($user['GOLOS_address_provided']=='0'){$user['GOLOS_address']='';}
if($user['BTS_address_provided']=='0'){$user['BTS_address']='';}

if($user['ethereum_address_provided']=='1'){
$query = $db->prepare("SELECT wallet_address FROM user_wallets WHERE uid = ? AND currency = ? AND product_id = ? ORDER BY id DESC");
$query->execute(array($user['id'],'ETH',$product['id']));
$user['ethereum_address'] = $query->fetch(\PDO::FETCH_ASSOC)['wallet_address'];
}

if($user['SBD_address_provided']=='1'){
$query = $db->prepare("SELECT wallet_address FROM user_wallets WHERE uid = ? AND currency = ? AND product_id = ? ORDER BY id DESC");
$query->execute(array($user['id'],'SBD',$product['id']));
$user['SBD_address'] = $query->fetch(\PDO::FETCH_ASSOC)['wallet_address'];
}

if($user['GOLOS_address_provided']=='1'){
$query = $db->prepare("SELECT wallet_address FROM user_wallets WHERE uid = ? AND currency = ? AND product_id = ? ORDER BY id DESC");
$query->execute(array($user['id'],'GOLOS',$product['id']));
$user['GOLOS_address'] = $query->fetch(\PDO::FETCH_ASSOC)['wallet_address'];
}

if($user['BTS_address_provided']=='1'){
$query = $db->prepare("SELECT wallet_address FROM user_wallets WHERE uid = ? AND currency = ? AND product_id = ? ORDER BY id DESC");
$query->execute(array($user['id'],'BTS',$product['id']));
$user['BTS_address'] = $query->fetch(\PDO::FETCH_ASSOC)['wallet_address'];
}

}

#Реферальная ссылка
if(!$logged_in['flag']){
if(!isset($_SESSION['ref'])){$_SESSION['ref']=NULL;}
if(isset($_GET['ref']) && is_numeric($_GET['ref']) && $_GET['ref']>0){
$query = $db->prepare("SELECT isactive FROM users WHERE id = ?");
$query->execute(array($_GET['ref']));
if($query->rowCount()>0 && $query->fetch(\PDO::FETCH_ASSOC)['isactive']=='1'){$_SESSION['ref']=$_GET['ref'];header('location: /signup/');exit;}else{$_SESSION['ref']=NULL;}
}}

function answer_json($status,$message='',$array=array()){header('Content-type:application/json;charset=utf-8');$encode=array_merge(array('status'=>$status,'message'=>$message),$array);echo json_encode($encode);exit();}

#Mail
$mail = new PHPMailer(true);								// Passing `true` enables exceptions

function sendmail($to,$email_images,$subject,$body,$altbody){
global $mail,$product,$lng;
$mail->setLanguage('en');
try {
    //Server settings
    $mail->isSMTP();										// Set mailer to use SMTP
    $mail->Host = $product['smtp_host'];					// Specify main and backup SMTP servers
    $mail->SMTPAuth = true;									// Enable SMTP authentication
    $mail->Username = $product['email'];					// SMTP username
    $mail->Password = $product['smtp_password'];			// SMTP password
    $mail->SMTPSecure = $product['smtp_security'];			// Enable TLS encryption, `ssl` also accepted
    $mail->Port = $product['smtp_port'];					// TCP port to connect to
	$mail->CharSet = 'UTF-8';
    $mail->setFrom($product['email'],$product['name']);
    $mail->addAddress($to);

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $body;
    $mail->AltBody    = $altbody;
	
	foreach($email_images as $image){$mail->AddEmbeddedImage(__DIR__.'/assets/products/'.$product['id'].'/mail/'.$image.'.png',$image,$image);}

    $mail->send();
} catch (Exception $e) {
file_put_contents('mailer_errors',$mail->ErrorInfo.PHP_EOL,FILE_APPEND);
$return['status']='error';
return $return;
}
}
?>
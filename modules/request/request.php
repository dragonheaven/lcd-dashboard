<?php
require '../../engine.php';

#Если пользователь не авторизован
if(!$logged_in['flag'] || $_SESSION['two_factor']!='' || !isset($_POST['do'])){answer_json('not_signed_in','');exit;}

#Обновление профиля
if($_POST['do']=='profile'){

$ethereum=filter_input(INPUT_POST,'ethereum',FILTER_SANITIZE_STRING);

#Если нет изменений
if(($ethereum=='' || $ethereum==$user['ethereum_address']) && $_POST['pw']=='0'){answer_json('no_change','no_change');}

if(($ethereum!='' && $ethereum!=$user['ethereum_address']) && (strlen($ethereum)!=42 || substr($ethereum,0,2)!='0x')){answer_json('ethereum',$lng['error_invalid_ethereum']);}

#Смена пароля
if($_POST['pw']=='1'){

$current_password=filter_input(INPUT_POST,'current_password',FILTER_SANITIZE_STRING);
$new_password=filter_input(INPUT_POST,'new_password',FILTER_SANITIZE_STRING);
$new_password_confirmation=filter_input(INPUT_POST,'new_password_confirmation',FILTER_SANITIZE_STRING);

$password_change=$auth->password_change($user['id'],$current_password,$new_password,$new_password_confirmation);
if($password_change['status']!='success'){answer_json($password_change['status'],$password_change['message']);}
}

if($ethereum!=''){$ethereum_provided='1';}else{$ethereum_provided='0';}

$query=$db->prepare("UPDATE users SET ethereum_address_provided=? WHERE id=? AND product_id=?");
if(!$query->execute(array_values(array($ethereum_provided,$user['id'],$product['id'])))){answer_json('error',$lng['error_system']);}

if($ethereum_provided=='1'){
$query=$db->prepare("INSERT INTO user_wallets (uid, wallet_address, product_id, time) VALUES (?, ?, ?, ?)");
$query->execute(array($user['id'],$ethereum,$product['id'],time()));
}

answer_json('success',$lng['message_profile_updated']);

}

#Получить адрес кошелька через Coinpayments API для покупки токенов
if($_POST['do']=='deposit_address'){

$currency=filter_input(INPUT_POST,'currency',FILTER_SANITIZE_STRING);

if($currency_settings[$currency]!='1'){answer_json('error','wrong_currency');}

$query=$db->prepare("SELECT wallet_address FROM deposit_wallets WHERE uid=? AND currency=? AND product_id=?");
$query->execute(array($user['id'],$currency,$product['id']));

#Определить, в $ стоимость токена или в Ethereum
if($product['token_price_eth']!='0' && $product['token_price_eth']>0){$tkn_price=$product['token_price_eth'];$currency_to_currency=round($currency_to_eth[$currency],5,PHP_ROUND_HALF_DOWN);}else{$tkn_price=$product['token_price'];$currency_to_currency=round($currency_to_usd[$currency],5,PHP_ROUND_HALF_DOWN);}

if($query->rowCount()>0){$data=$query->fetch(\PDO::FETCH_ASSOC);answer_json('success',$data['wallet_address'],array('token_price'=>$tkn_price,'currency_to_usd'=>$currency_to_currency));}

while($deposit_address==''){
$deposit_address=getCoinPaymentsWalletAddress($currency,$product['coinpayments_public_key'],$product['coinpayments_private_key']);
}

$query=$db->prepare("INSERT INTO deposit_wallets (uid, currency, wallet_address, product_id, time) VALUES (?, ?, ?, ?, ?)");
if($query->execute(array($user['id'],$currency,$deposit_address,$product['id'],time()))){answer_json('success',$deposit_address,array('token_price'=>$tkn_price,'currency_to_usd'=>$currency_to_currency));}
}

#Техподдержка пользователей
if($_POST['do']=='support'){

$subject=filter_input(INPUT_POST,'subject',FILTER_SANITIZE_NUMBER_INT);
$message=filter_input(INPUT_POST,'message',FILTER_SANITIZE_STRING);

if($subject=='' || ($subject!='1' && $subject!='2')){answer_json('error',$lng['support_error_subject']);}
if($message==''){answer_json('error',$lng['support_error_message']);}

$query=$db->prepare("INSERT INTO support (uid, subject, message, time, product_id) VALUES (?, ?, ?, ?, ?)");
if($query->execute(array($user['id'],$subject,$message,time(),$product['id']))){

#Отправка email администратору технической поддержки
if($product['support_email']!=''){
$sendmail=sendmail($product['support_email'],array(),'Support request - '.$product['name'],'Email: '.$user['email'].'<br>Subject: '.$subject.'<br>Message: '.$message,'Email: '.$user['email'].'\n\rSubject: '.$subject.'\n\rMessage: '.$message);
}

answer_json('success',$lng['support_success']);}

}

#Настройка двухэтапной аутенфикации
if($_POST['do']=='two_factor_settings'){

$act=filter_input(INPUT_POST,'act',FILTER_SANITIZE_STRING);
$two_factor_code=filter_input(INPUT_POST,'two_factor_code',FILTER_SANITIZE_NUMBER_INT);

if($act!='enable' && $act!='disable'){answer_json('error',$lng['error_system']);}
if($act=='enable' && !isset($_SESSION['two_factor_secret'])){answer_json('error',$lng['error_system']);}

if($act=='enable'){

require_once '../auth/GoogleAuthenticator.php';
$ga=new GoogleAuthenticator();

if(strlen($two_factor_code)!=6 || !is_numeric($two_factor_code) || !$ga->verifyCode($_SESSION['two_factor_secret'],$two_factor_code)){answer_json('error',$lng['two_factor_code_error']);}

$update=$_SESSION['two_factor_secret'];}else{$update='';}

$query=$db->prepare("UPDATE users SET two_factor=? WHERE id=? AND product_id=?");
if(!$query->execute(array_values(array($update,$user['id'],$product['id'])))){answer_json('error',$lng['error_system']);}else{unset($_SESSION['two_factor_secret']);answer_json('success','');}

}

if($user['admin']=='1'){

#Панель администратора: основные данные
if($_POST['do']=='admin_main'){

$name=filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);
$short_name=filter_input(INPUT_POST,'short_name',FILTER_SANITIZE_STRING);
$url=filter_input(INPUT_POST,'url',FILTER_SANITIZE_URL);
$url_main=filter_input(INPUT_POST,'url_main',FILTER_SANITIZE_URL);
$user_agreement=filter_input(INPUT_POST,'user_agreement',FILTER_SANITIZE_URL);
$footer_copyright=filter_input(INPUT_POST,'footer_copyright',FILTER_SANITIZE_STRING);
$token_name=filter_input(INPUT_POST,'token_name',FILTER_SANITIZE_STRING);
$smtp_host=filter_input(INPUT_POST,'smtp_host',FILTER_SANITIZE_STRING);
$email=strtolower(filter_input(INPUT_POST,'email',FILTER_SANITIZE_EMAIL));
$smtp_password=filter_input(INPUT_POST,'smtp_password',FILTER_SANITIZE_STRING);
$smtp_security=filter_input(INPUT_POST,'smtp_security',FILTER_SANITIZE_STRING);
$smtp_port=filter_input(INPUT_POST,'smtp_port',FILTER_SANITIZE_NUMBER_INT);
$smartcontract_address=filter_input(INPUT_POST,'smartcontract_address',FILTER_SANITIZE_STRING);
$smartcontract_creator=filter_input(INPUT_POST,'smartcontract_creator',FILTER_SANITIZE_STRING);
$coinpayments_public_key=filter_input(INPUT_POST,'coinpayments_public_key',FILTER_SANITIZE_STRING);
$coinpayments_private_key=filter_input(INPUT_POST,'coinpayments_private_key',FILTER_SANITIZE_STRING);
$coinpayments_ipn_secret=filter_input(INPUT_POST,'coinpayments_ipn_secret',FILTER_SANITIZE_STRING);
$coinpayments_merchant_id=filter_input(INPUT_POST,'coinpayments_merchant_id',FILTER_SANITIZE_STRING);
$etherscan_api_key=filter_input(INPUT_POST,'etherscan_api_key',FILTER_SANITIZE_STRING);
$twitter_consumer_key=filter_input(INPUT_POST,'twitter_consumer_key',FILTER_SANITIZE_STRING);
$twitter_consumer_secret=filter_input(INPUT_POST,'twitter_consumer_secret',FILTER_SANITIZE_STRING);
$facebook_app_id=filter_input(INPUT_POST,'facebook_app_id',FILTER_SANITIZE_STRING);
$facebook_app_secret=filter_input(INPUT_POST,'facebook_app_secret',FILTER_SANITIZE_STRING);
$google_oauth=filter_input(INPUT_POST,'google_oauth',FILTER_SANITIZE_NUMBER_INT);if($google_oauth==''){$google_oauth='0';}
$recaptcha_site_key=filter_input(INPUT_POST,'recaptcha_site_key',FILTER_SANITIZE_STRING);
$recaptcha_secret=filter_input(INPUT_POST,'recaptcha_secret',FILTER_SANITIZE_STRING);
$captcha_enabled=filter_input(INPUT_POST,'captcha_enabled',FILTER_SANITIZE_NUMBER_INT);if($captcha_enabled==''){$captcha_enabled='0';}
$bitcointalk=filter_input(INPUT_POST,'bitcointalk',FILTER_SANITIZE_URL);
$facebook=filter_input(INPUT_POST,'facebook',FILTER_SANITIZE_URL);
$twitter=filter_input(INPUT_POST,'twitter',FILTER_SANITIZE_URL);
$telegram=filter_input(INPUT_POST,'telegram',FILTER_SANITIZE_URL);
$vk=filter_input(INPUT_POST,'vk',FILTER_SANITIZE_URL);
$youtube=filter_input(INPUT_POST,'youtube',FILTER_SANITIZE_URL);

if($name==''){answer_json('name',$lng['error_field_required']);}
if($short_name==''){answer_json('short_name',$lng['error_field_required']);}
if($url==''){answer_json('url',$lng['error_field_required']);}
if($url_main==''){answer_json('url_main',$lng['error_field_required']);}
if(!isset($_POST['user_agreement']) || (strlen($_POST['user_agreement'])>0 && $user_agreement=='')){answer_json('user_agreement',$lng['error_field_required']);}
if($footer_copyright==''){answer_json('footer_copyright',$lng['error_field_required']);}
if($token_name==''){answer_json('token_name',$lng['error_field_required']);}
if($smtp_host==''){answer_json('smtp_host',$lng['error_field_required']);}
if($email==''){answer_json('email',$lng['error_invalid_email']);}
if($smtp_password==''){answer_json('smtp_password',$lng['error_field_required']);}
if($smtp_security!='ssl' && $smtp_security!='tls'){answer_json('smtp_security',$lng['error_field_required']);}
if($smtp_port=='' || $smtp_port<=0){answer_json('smtp_port',$lng['error_field_required']);}
if(($smartcontract_address=='') || (strlen($smartcontract_address)!=42 || substr($smartcontract_address,0,2)!='0x')){answer_json('smartcontract_address',$lng['error_invalid_ethereum']);}
if(($smartcontract_creator=='') || (strlen($smartcontract_creator)!=42 || substr($smartcontract_creator,0,2)!='0x')){answer_json('smartcontract_creator',$lng['error_invalid_ethereum']);}
if($coinpayments_public_key==''){answer_json('coinpayments_public_key',$lng['error_field_required']);}
if($coinpayments_private_key==''){answer_json('coinpayments_private_key',$lng['error_field_required']);}
if($coinpayments_ipn_secret==''){answer_json('coinpayments_ipn_secret',$lng['error_field_required']);}
if($coinpayments_merchant_id==''){answer_json('coinpayments_merchant_id',$lng['error_field_required']);}
if($etherscan_api_key==''){answer_json('etherscan_api_key',$lng['error_field_required']);}
if($twitter_consumer_key==''){answer_json('twitter_consumer_key',$lng['error_field_required']);}
if($twitter_consumer_secret==''){answer_json('twitter_consumer_secret',$lng['error_field_required']);}
if($facebook_app_id==''){answer_json('facebook_app_id',$lng['error_field_required']);}
if($facebook_app_secret==''){answer_json('facebook_app_secret',$lng['error_field_required']);}
if($google_oauth!='0' && $google_oauth!='1'){answer_json('google_oauth',$lng['error_field_required']);}
if($recaptcha_site_key==''){answer_json('recaptcha_site_key',$lng['error_field_required']);}
if($recaptcha_secret==''){answer_json('recaptcha_secret',$lng['error_field_required']);}
if($captcha_enabled!='0' && $captcha_enabled!='1'){answer_json('captcha_enabled',$lng['error_field_required']);}
if($bitcointalk==''){answer_json('bitcointalk',$lng['error_field_required']);}
if($facebook==''){answer_json('facebook',$lng['error_field_required']);}
if($twitter==''){answer_json('twitter',$lng['error_field_required']);}
if($telegram==''){answer_json('telegram',$lng['error_field_required']);}
if($vk==''){answer_json('vk',$lng['error_field_required']);}
if($youtube==''){answer_json('youtube',$lng['error_field_required']);}

$query=$db->prepare("UPDATE products SET name=?, short_name=?, url=?, url_main=?, user_agreement=?, footer_copyright=?, token_name=?, smtp_host=?, email=?, smtp_password=?, smtp_security=?, smtp_port=?, smartcontract_address=?, smartcontract_creator=?, coinpayments_public_key=?, coinpayments_private_key=?, coinpayments_ipn_secret=?, coinpayments_merchant_id=?, etherscan_api_key=?, twitter_consumer_key=?, twitter_consumer_secret=?, facebook_app_id=?, facebook_app_secret=?, google_oauth=?, recaptcha_site_key=?, recaptcha_secret=?, captcha_enabled=?, bitcointalk=?, facebook=?, twitter=?, telegram=?, vk=?, youtube=? WHERE id=?");
if(!$query->execute(array_values(array($name,$short_name,$url,$url_main,$user_agreement,$footer_copyright,$token_name,$smtp_host,$email,$smtp_password,$smtp_security,$smtp_port,$smartcontract_address,$smartcontract_creator,$coinpayments_public_key,$coinpayments_private_key,$coinpayments_ipn_secret,$coinpayments_merchant_id,$etherscan_api_key,$twitter_consumer_key,$twitter_consumer_secret,$facebook_app_id,$facebook_app_secret,$google_oauth,$recaptcha_site_key,$recaptcha_secret,$captcha_enabled,$bitcointalk,$facebook,$twitter,$telegram,$vk,$youtube,$product['id'])))){answer_json('error',$lng['error_system']);}else{answer_json('success',$lng['admin_success']);}

}

#Панель администратора: настройки
if($_POST['do']=='admin_settings'){

$token_price=filter_input(INPUT_POST,'token_price',FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$token_price_eth=filter_input(INPUT_POST,'token_price_eth',FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$bonus_percent=filter_input(INPUT_POST,'bonus_percent',FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$referral_percent=filter_input(INPUT_POST,'referral_percent',FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$signup_tokens=filter_input(INPUT_POST,'signup_tokens',FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$support_email=filter_input(INPUT_POST,'support_email',FILTER_SANITIZE_STRING);
$email_activation=filter_input(INPUT_POST,'email_activation',FILTER_SANITIZE_NUMBER_INT);if($email_activation==''){$email_activation='0';}
$ethereum_field_required=filter_input(INPUT_POST,'ethereum_field_required',FILTER_SANITIZE_NUMBER_INT);if($ethereum_field_required==''){$ethereum_field_required='0';}
$SBD_wallet=filter_input(INPUT_POST,'SBD_wallet',FILTER_SANITIZE_STRING);
$GOLOS_wallet=filter_input(INPUT_POST,'GOLOS_wallet',FILTER_SANITIZE_STRING);
$BTS_wallet=filter_input(INPUT_POST,'BTS_wallet',FILTER_SANITIZE_STRING);

$currency_array=array();

foreach($currency_settings as $letters=>$enabled){
$currency_array[$letters]=filter_input(INPUT_POST,'currency_'.$letters.'',FILTER_SANITIZE_NUMBER_INT);
if($currency_array[$letters]!='' && $currency_array[$letters]!='1'){answer_json('currency',$lng['error_field_required']);}
if($currency_array[$letters]==''){$currency_array[$letters]='0';}
}

if(is_array($currency_array) && count($currency_array) > 0) {$customParamsQueryArray=Array();
foreach($currency_array as $paramKey => $paramValue) {$customParamsQueryArray[]=array('value' => $paramKey . '=?');}
$setParams=',' . implode(',',array_map(function ($entry) {return $entry['value'];}, $customParamsQueryArray));} else { $setParams=''; }
$setParams=ltrim($setParams,',');

if($token_price==''){answer_json('token_price',$lng['error_field_required']);}
if($token_price_eth==''){answer_json('token_price_eth',$lng['error_field_required']);}
if($bonus_percent==''){answer_json('bonus_percent',$lng['error_field_required']);}
if($referral_percent==''){answer_json('referral_percent',$lng['error_field_required']);}
if($signup_tokens=='' || $signup_tokens<0){answer_json('signup_tokens',$lng['error_field_required']);}
if($support_email==''){answer_json('support_email',$lng['error_field_required']);}
if($email_activation!='0' && $email_activation!='1'){answer_json('email_activation',$lng['error_field_required']);}
if($ethereum_field_required!='0' && $ethereum_field_required!='1'){answer_json('ethereum_field_required',$lng['error_field_required']);}
if($SBD_wallet=='' && $currency_array['SBD']=='1'){answer_json('SBD_wallet',$lng['error_field_required']);}
if($GOLOS_wallet=='' && $currency_array['GOLOS']=='1'){answer_json('GOLOS_wallet',$lng['error_field_required']);}
if($BTS_wallet=='' && $currency_array['BTS']=='1'){answer_json('BTS_wallet',$lng['error_field_required']);}

$query=$db->prepare("UPDATE currency_settings SET {$setParams} WHERE product_id=?");
if(!$query->execute(array_values(array_merge($currency_array, array($product['id']))))){answer_json('error','Currency - '.$lng['error_system']);}

$query=$db->prepare("UPDATE products SET token_price=?, token_price_eth=?, bonus_percent=?, referral_percent=?, signup_tokens=?, support_email=?, email_activation=?, ethereum_field_required=?, SBD_wallet=?, GOLOS_wallet=?, BTS_wallet=? WHERE id=?");
if(!$query->execute(array_values(array($token_price,$token_price_eth,$bonus_percent,$referral_percent,$signup_tokens,$support_email,$email_activation,$ethereum_field_required,$SBD_wallet,$GOLOS_wallet,$BTS_wallet,$product['id'])))){answer_json('error',$lng['error_system']);}else{answer_json('success',$lng['admin_success']);}

}

#Панель администратора: поиск пользователя
if($_POST['do']=='admin_search'){

$user_email=strtolower(filter_input(INPUT_POST,'user_email',FILTER_SANITIZE_EMAIL));

if($user_email==''){answer_json('user_email',$lng['error_invalid_email']);}

$query=$db->prepare("SELECT id, balance FROM users WHERE email=? AND isactive=? AND product_id=?");
$query->execute(array($user_email,'1',$product['id']));
if($query->rowCount()==0){answer_json('user_email',$lng['error_user_not_found']);}
$usr_row=$query->fetch(\PDO::FETCH_ASSOC);

answer_json('success',$lng['admin_tokens_user_id'].': '.$usr_row['id'].', Email: '.$user_email.', '.$lng['userlist_overall_balance'].': '.$usr_row['balance'],array('user_id'=>$usr_row['id']));

}

#Панель администратора: добавить или убавить токены
if($_POST['do']=='admin_tokens'){

$user_id=filter_input(INPUT_POST,'user_id',FILTER_SANITIZE_NUMBER_INT);
$currency=filter_input(INPUT_POST,'currency_',FILTER_SANITIZE_STRING);
$amount=filter_input(INPUT_POST,'amount',FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
$tokens_amount=filter_input(INPUT_POST,'tokens_amount',FILTER_SANITIZE_NUMBER_INT);
$act=filter_input(INPUT_POST,'act',FILTER_SANITIZE_STRING);
$comment=filter_input(INPUT_POST,'comment',FILTER_SANITIZE_STRING);

if($user_id==''){answer_json('user_id',$lng['error_field_required']);}
if($currency=='' || ($currency!='USD' && $currency_settings[$currency]!='1')){answer_json('currency',$lng['error_field_required']);}
if(!is_numeric($amount) || $amount=='' || $amount<=0){answer_json('amount',$lng['error_field_required']);}
if(!is_numeric($tokens_amount) || $tokens_amount=='' || $tokens_amount<=0){answer_json('tokens_amount',$lng['error_field_required']);}
if($act!='add' && $act!='remove'){answer_json('act',$lng['error_field_required']);}
if($comment==''){answer_json('comment',$lng['error_field_required']);}

if($currency=='USD'){$system='paypal';$usd_add=$amount;}else{$system='cryptocurrency';$usd_add=0;}

if($act=='remove'){$amount=$amount*-1;$tokens_amount=$tokens_amount*-1;}

$query=$db->prepare("SELECT email, balance FROM users WHERE isactive=? AND id=? AND product_id=?");
$query->execute(array('1',$user_id,$product['id']));
if($query->rowCount()==0){answer_json('user_id',$lng['error_user_not_found']);}
$usr_row=$query->fetch(\PDO::FETCH_ASSOC);

$balance_new=$usr_row['balance']+$tokens_amount;
if($balance_new<0){answer_json('tokens_amount',$usr_row['email'].' '.$lng['userlist_overall_balance'].': '.$usr_row['balance']);}

$query = $db->prepare("INSERT INTO balance_actions (admin, uid, type, amount, tokens_amount, comment, time, product_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
if(!$query->execute(array($user['id'], $user_id, $act, $amount, $tokens_amount, $comment, time(), $product['id']))){answer_json('error',$lng['error_system']);}

$query = $db->prepare("INSERT INTO payments (uid, amount, currency, status, status_text, tokens_amount, system, time, product_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$query->execute(array($user_id,$amount,$currency,'100','Manual',$tokens_amount,$system,time(),$product['id']));

$query=$db->prepare("UPDATE products SET usd_raised=?, tokens_sold=? WHERE id=?");
$query->execute(array_values(array($product['usd_raised']+$usd_add,$product['tokens_sold']+$tokens_amount,$product['id'])));

$query=$db->prepare("UPDATE users SET balance=? WHERE id=? AND product_id=?");
if(!$query->execute(array_values(array($balance_new,$user_id,$product['id'])))){answer_json('error',$lng['error_system']);}else{answer_json('success','Операция успешна. Новый баланс пользователя: <b>'.$balance_new.'</b>');}

}

}
?>
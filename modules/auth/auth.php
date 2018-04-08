<?php
require '../../engine.php';

#Выход из аккаунта
if(isset($_GET['do']) && $_GET['do']=='signout' && isset($_GET['hash'])){
$hash=filter_input(INPUT_GET,'hash',FILTER_SANITIZE_STRING);
if($logged_in['flag'] && $hash==$_SESSION['authID']){$signout=$auth->signout($hash);}
header('location: https://'.$_SERVER['HTTP_HOST'].'/');exit;
}


#OAuth вход или регистрация
if(isset($_GET['oauth']) && ($_GET['oauth']=='google' || $_GET['oauth']=='twitter' || $_GET['oauth']=='facebook')){

#Google
if($_GET['oauth']=='google'){

if(!isset($_SESSION['google_user_data'])){header('location: https://'.$_SERVER['HTTP_HOST'].'/signin/');exit;}
$google=$_SESSION['google_user_data'];
$oauth_id=$google['id'];

$special_fields=array();
foreach($google as $arr=>$val){
if($val!=''){
if($arr=='email'){$special_fields['oauth_email']=$val;}
if($arr=='name'){$special_fields['first_name']=$val;}
if($arr=='link'){$special_fields['google_link']=$val;}
if($arr=='gender'){$special_fields['gender']=$val;}}
}

unset($_SESSION['google_user_data']);

}

#Twitter
if($_GET['oauth']=='twitter'){

if(!isset($_SESSION['twitter_user_data'])){header('location: https://'.$_SERVER['HTTP_HOST'].'/signin/');exit;}
$twitter=$_SESSION['twitter_user_data'];
$oauth_id=$twitter['id'];

$special_fields=array();
foreach($twitter as $arr=>$val){
if($val!=''){
if($arr=='email'){$special_fields['oauth_email']=$val;}
if($arr=='name'){$special_fields['twitter_name']=$val;}
if($arr=='screen_name'){$special_fields['twitter_username']=$val;}
if($arr=='location'){$special_fields['twitter_location']=$val;}
if($arr=='url'){$special_fields['twitter_website']=$val;}
if($arr=='followers'){$special_fields['twitter_followers']=$val;}
if($arr=='friends'){$special_fields['twitter_friends']=$val;}
if($arr=='created_at'){$special_fields['twitter_created_at']=$val;}
if($arr=='lang'){$special_fields['twitter_lang']=$val;}
if($arr=='time_zone'){$special_fields['twitter_time_zone']=$val;}
if($arr=='verified'){$special_fields['twitter_verified']=$val;}
if($arr=='profile_image_url_https'){$special_fields['twitter_image']=$val;}}
}

unset($_SESSION['twitter_user_data']);

}

#Facebook
if($_GET['oauth']=='facebook'){

if(!isset($_SESSION['facebook_user_data'])){header('location: https://'.$_SERVER['HTTP_HOST'].'/signin/');exit;}
$facebook=$_SESSION['facebook_user_data'];
$oauth_id=$facebook['id'];

$special_fields=array();
foreach($facebook as $arr=>$val){
if($val!=''){
if($arr=='email'){$special_fields['oauth_email']=$val;}
if($arr=='first_name'){$special_fields['first_name']=$val;}
if($arr=='last_name'){$special_fields['last_name']=$val;}
if($arr=='birthday'){$special_fields['facebook_birthday']=$val;}
if($arr=='website'){$special_fields['facebook_website']=$val;}
if($arr=='gender'){$special_fields['gender']=$val;}
//if($arr=='location'){$special_fields['facebook_location']=$val['name'];}
}
}

unset($_SESSION['facebook_user_data']);

}

#Если пользователь авторизован, соединяем новые данные с аккаунтом пользователя
if($logged_in['flag']){

if(is_array($special_fields) && count($special_fields)>0){
$customParamsQueryArray = Array();
foreach($special_fields as $paramKey => $paramValue) {$customParamsQueryArray[] = array('value' => $paramKey . ' = ?');}
$setParams = ', ' . implode(', ', array_map(function ($entry) {return $entry['value'];}, $customParamsQueryArray));} else { $setParams = ''; }

$query = $db->prepare("UPDATE users SET oauth_{$_GET['oauth']} = ? {$setParams} WHERE id = ? AND product_id=?");
$query->execute(array_values(array_merge(array($oauth_id), $special_fields, array($user['id']), array($product['id']))));
header('location: https://'.$_SERVER['HTTP_HOST'].'/');exit;
}

#Если пользователь не зарегистрирован, регистрируем
$query=$db->prepare("SELECT id FROM users WHERE oauth_id=? AND oauth_service=? AND product_id=?");
$query->execute(array($oauth_id,$_GET['oauth'],$product['id']));

if($query->rowCount()==0){

$signup_oauth=$auth->signup_OAuth($oauth_id,$_GET['oauth'],array_merge($special_fields,array('balance_bonus'=>$product['signup_tokens'],'ip'=>$user['ip'],'country_code'=>$user['country_code'],'referrer'=>$_SESSION['ref'])));
if($signup_oauth['status']!='success'){answer_json($signup_oauth['status'],$signup_oauth['message']);}

if($product['signup_tokens']>'0'){
$query = $db->prepare("UPDATE products SET tokens_signup = ? WHERE id = ?");
$query->execute(array(($product['tokens_signup']+$product['signup_tokens']), $product['id']));
}

}

#Вход
$signin_oauth=$auth->signin_OAuth($oauth_id,$_GET['oauth']);
header('location: https://'.$_SERVER['HTTP_HOST'].'/');exit;
}


if(isset($_POST['do'])){

#Если пользователь залогинен, прекращаем выполнение
if($logged_in['flag']){



#Двухфакторная авторизация
if($_POST['do']=='two_factor' && $_SESSION['two_factor']!=''){

require_once 'GoogleAuthenticator.php';
$ga=new GoogleAuthenticator();

$two_factor_code=filter_input(INPUT_POST,'two_factor_code',FILTER_SANITIZE_NUMBER_INT);

if(strlen($two_factor_code)!=6 || !is_numeric($two_factor_code) || !$ga->verifyCode($user['two_factor'],$two_factor_code)){answer_json('error',$lng['two_factor_code_error']);}else{$_SESSION['two_factor']='';answer_json('success','');}

}

    

answer_json('signed_in',$lng['auth_error_signed_in']);}

#Регистрация
if($_POST['do']=='signup'){

$email=strtolower(filter_input(INPUT_POST,'email',FILTER_SANITIZE_STRING));
$password=filter_input(INPUT_POST,'password',FILTER_SANITIZE_STRING);
$password_confirmation=filter_input(INPUT_POST,'password_confirmation',FILTER_SANITIZE_STRING);
$ethereum=filter_input(INPUT_POST,'ethereum',FILTER_SANITIZE_STRING);
$agree=filter_input(INPUT_POST,'agree',FILTER_SANITIZE_NUMBER_INT);
$captcha=filter_input(INPUT_POST,'captcha',FILTER_SANITIZE_STRING);
$recaptcha=filter_input(INPUT_POST,'g-recaptcha-response',FILTER_SANITIZE_STRING);

if(((strlen($ethereum)!=42 || substr($ethereum,0,2)!='0x') && $ethereum!='') || $product['ethereum_field_required']=='1'){answer_json('ethereum',$lng['error_invalid_ethereum']);}
if($agree!=1 && $product['user_agreement']!=''){answer_json('agree','please_agree');}

if($product['captcha_enabled']=='1' && (!isset($_SESSION['captcha_answer']) || $captcha!=$_SESSION['captcha_answer'])){unset($_SESSION['captcha_answer']);answer_json('captcha',$lng['error_captcha']);}

if($ethereum!=''){$ethereum_provided='1';}else{$ethereum_provided='0';}

$signup=$auth->signup($email,$password,$password_confirmation,array('balance_bonus'=>$product['signup_tokens'],'ip'=>$user['ip'],'country_code'=>$user['country_code'],'ethereum_address_provided'=>$ethereum_provided,'p'=>$password,'referrer'=>$_SESSION['ref']),$product['email_activation'],$recaptcha);
if($signup['status']!='success'){answer_json($signup['status'],$signup['message']);}else{

unset($_SESSION['captcha_answer']);

if($product['email_activation']=='1'){

#Отправка email
$sendmail=sendmail($email,array('logo','bt','fb','tw','tg','vk','yt','logo2','bt_sm','fb_sm','tw_sm','tg_sm','vk_sm','yt_sm'),$lng['email_signup_subject'],sprintf(file_get_contents('../../assets/products/'.$product['id'].'/mail/mail.html'),$product['url'],$lng['language_code'],$product['name'],$product['footer_copyright'],$product['bitcointalk'],$product['facebook'],$product['twitter'],$product['telegram'],$product['vk'],$product['youtube'],$lng['email_signup_subject'],$lng['email_greeting'],$lng['email_signup_message'],$lng['email_signup_mistake'],$lng['email_please_ignore'],$lng['email_visit_website'],'signup/'.$signup['code'].'/'.$signup['request_id'].'/'),$lng['email_greeting_alt'].$lng['email_signup_message_alt'].' '.$product['url'].'signup/'.$signup['code'].'/'.$signup['request_id'].'/\n\n'.$lng['email_signup_mistake_alt'].$lng['email_please_ignore']);

if($sendmail['status']=='error'){$auth->request_delete($signup['request_id']);answer_json('error','Email - '.$lng['error_system']);}

}

if($product['signup_tokens']>'0' && $product['email_activation']=='0'){
$query = $db->prepare("UPDATE products SET tokens_signup = ? WHERE id = ?");
$query->execute(array(($product['tokens_signup']+$product['signup_tokens']), $product['id']));
}

if($ethereum_provided=='1'){
$query = $db->prepare("INSERT INTO user_wallets (uid, wallet_address, product_id, time) VALUES (?, ?, ?, ?)");
$query->execute(array($signup['uid'],$ethereum,$product['id'],time()));
}

#Вход после регистрации
if($product['email_activation']=='0'){
$signin=$auth->signin($email,$password);
if($signin['status']!='success'){answer_json($signin['status'],$signin['message']);}else{answer_json($signin['status'],'');}
}

answer_json('activation',sprintf($lng['message_activation'],$signup['uid']));

}
}

#Вход
if($_POST['do']=='signin'){
$email=filter_input(INPUT_POST,'email',FILTER_SANITIZE_STRING);
$password=filter_input(INPUT_POST,'password',FILTER_SANITIZE_STRING);
$signin=$auth->signin($email,$password);
if($signin['status']!='success'){answer_json($signin['status'],$signin['message']);}else{answer_json($signin['status'],'');}
}

#Запрос ключа восстановления для смены пароля
if($_POST['do']=='reset'){

$email=filter_input(INPUT_POST,'email',FILTER_SANITIZE_STRING);

$password_reset_request=$auth->password_reset_request($email);
if($password_reset_request['status']!='success'){answer_json($password_reset_request['status'],$password_reset_request['message']);}else{

#Отправка email
$sendmail=sendmail($email,array('logo','bt','fb','tw','tg','vk','yt','logo2','bt_sm','fb_sm','tw_sm','tg_sm','vk_sm','yt_sm'),$lng['email_reset_subject'],sprintf(file_get_contents('../../assets/products/'.$product['id'].'/mail/mail.html'),$product['url'],$lng['language_code'],$product['name'],$product['footer_copyright'],$product['bitcointalk'],$product['facebook'],$product['twitter'],$product['telegram'],$product['vk'],$product['youtube'],$lng['email_reset_subject'],$lng['email_greeting'],$lng['email_reset_message'],$lng['email_reset_mistake'],$lng['email_please_ignore'],$lng['email_visit_website'],'reset/'.$password_reset_request['code'].'/'.$password_reset_request['request_id'].'/'),$lng['email_greeting_alt'].$lng['email_reset_message_alt'].' '.$product['url'].'reset/'.$password_reset_request['code'].'/'.$password_reset_request['request_id'].'/\n\n'.$lng['email_reset_mistake_alt'].$lng['email_please_ignore']);

if($sendmail['status']=='error'){$auth->request_delete($password_reset_request['request_id']);answer_json('error','Email - '.$lng['error_system']);}

answer_json('success',$password_reset_request['message']);}
}

#Запрос повторного письма для регистрации
if($_POST['do']=='activation_resend'){

if($product['email_activation']!='1'){answer_json('error','');}

$user_id=filter_input(INPUT_POST,'user_id',FILTER_SANITIZE_NUMBER_INT);

if(!is_numeric($user_id) || $user_id<=0){answer_json('error','');}

$activation_resend=$auth->activation_resend($user_id);
if($activation_resend['status']!='success'){if($activation_resend['status']=='time'){answer_json('time',($activation_resend['time']*1000));}else{answer_json('error','');}}

#Отправка email
$sendmail=sendmail($activation_resend['user_email'],array('logo','bt','fb','tw','tg','vk','yt','logo2','bt_sm','fb_sm','tw_sm','tg_sm','vk_sm','yt_sm'),$lng['email_signup_subject'],sprintf(file_get_contents('../../assets/products/'.$product['id'].'/mail/mail.html'),$product['url'],$lng['language_code'],$product['name'],$product['footer_copyright'],$product['bitcointalk'],$product['facebook'],$product['twitter'],$product['telegram'],$product['vk'],$product['youtube'],$lng['email_signup_subject'],$lng['email_greeting'],$lng['email_signup_message'],$lng['email_signup_mistake'],$lng['email_please_ignore'],$lng['email_visit_website'],'signup/'.$activation_resend['code'].'/'.$activation_resend['request_id'].'/'),$lng['email_greeting_alt'].$lng['email_signup_message_alt'].' '.$product['url'].'signup/'.$activation_resend['code'].'/'.$activation_resend['request_id'].'/\n\n'.$lng['email_signup_mistake_alt'].$lng['email_please_ignore']);

if($sendmail['status']=='error'){$auth->request_delete($activation_resend['request_id']);answer_json('error','Email - '.$lng['error_system']);}

answer_json('time',(180*1000));

}

#Смена пароля с помощью ключа восстановления
if($_POST['do']=='reset_code'){

$code=filter_input(INPUT_POST,'code',FILTER_SANITIZE_STRING);
$request_id=filter_input(INPUT_POST,'request_id',FILTER_SANITIZE_NUMBER_INT);
$new_password=filter_input(INPUT_POST,'new_password',FILTER_SANITIZE_STRING);
$new_password_confirmation=filter_input(INPUT_POST,'new_password_confirmation',FILTER_SANITIZE_STRING);

if($code=='' || $request_id=='' || !is_numeric($request_id)){answer_json('error','invalid_code');}
$request_get=$auth->request_get($code,$request_id,'reset');
if($request_get['status']!='success'){answer_json('error',$request_get['message']);}

$password_reset=$auth->password_reset($code,$request_id,$new_password,$new_password_confirmation);
if($password_reset['status']!='success'){answer_json($password_reset['status'],$password_reset['message']);}

answer_json($password_reset['status'],'');

}

}
?>
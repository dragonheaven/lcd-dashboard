<?php
if(!isset($settings) || ($logged_in['flag'] && $_SESSION['two_factor']=='')){header('location: /');exit;}


if($_SESSION['two_factor']!=''){$module='two_factor';}


$page_title=$lng['title_'.$module.''];

if($module=='signin'){$href='signup';}
if($module=='signup' || $module=='reset'){$href='signin';}

if($module=='signup' && isset($_GET['code']) && isset($_GET['request_id']) && is_numeric($_GET['request_id']) && $_GET['request_id']>0 && $product['email_activation']=='1'){
$module='signup_code';

$code=filter_input(INPUT_GET,'code',FILTER_SANITIZE_STRING);
$request_id=filter_input(INPUT_GET,'request_id',FILTER_SANITIZE_NUMBER_INT);

$activation=$auth->activation($code,$request_id);
if($activation['status']=='success'){

if($auth->auth($activation['uid'])){

if($product['signup_tokens']>'0'){
$query = $db->prepare("UPDATE products SET tokens_signup = ? WHERE id = ?");
$query->execute(array(($product['tokens_signup']+$product['signup_tokens']), $product['id']));
}

header('location: /');exit;}
}
}

if($module=='reset' && isset($_GET['code']) && isset($_GET['request_id']) && is_numeric($_GET['request_id']) && $_GET['request_id']>0){
$code=filter_input(INPUT_GET,'code',FILTER_SANITIZE_STRING);
$request_id=filter_input(INPUT_GET,'request_id',FILTER_SANITIZE_NUMBER_INT);$module='reset_code';}
?><!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no" />
  <title><?=$product['name']?> — <?=$page_title?></title>
  <meta property="og:title" content="<?=$product['name']?> — <?=$page_title?>">
  <meta name="twitter:title" content="<?=$product['name']?> — <?=$page_title?>"/>
  <meta name="twitter:image:alt" content="<?=$product['name']?> — <?=$page_title?>">
  <link rel="stylesheet" href="/assets/products/<?=$product['id']?>/css.css?v=1.0" />
  <link rel='stylesheet prefetch' href='//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>
<style type="text/css">
.scl-nav {
  padding: 0;
  list-style: none;
  display: inline-block;
  margin: 10px auto;
}
.scl-nav li {
  display: inline-block;
  margin: 0 2px;
}
.scl-nav a {
  display: inline-block;
  float: left;
  width: 48px;
  height: 48px;
  font-size: 20px;
  color: #FFF;
  text-decoration: none;
  cursor: pointer;
  text-align: center;
  line-height: 48px;
  background: #000;
  position: relative;
  -moz-transition: 0.5s;
  -o-transition: 0.5s;
  -webkit-transition: 0.5s;
  transition: 0.5s;
  
  background: none;
  line-height: 1.5;
  font-size: 32px;
  text-shadow: 0px 0px 1px;
}

.scl-nav a:hover {  text-shadow: 0px 0px 25px;}
.scl-nav .twi {  color: #00ACED;}
.scl-nav .fc {  color: #3B579D;}
.scl-nav .g-p {  color: #DD4A3A;}
</style>
	<script src="/assets/js/jquery-3.2.1.min.js"></script>
</head>
<body>
	<nav class="top">
		<div class="container">
			<div class="top__right">
				<?php if($module!='two_factor'){?><div class="auth top__block"><a class="auth__signup" href="/<?=$href?>/"><?=$lng['auth_'.$href.'']?></a></div><?php }?>
				<ul class="menu top__block">
					<li class="menu__item"><a class="menu__link" href="<?=$product['url_main']?>/getlcd" target="_blank">#GETLCD</a></li>
					<li class="menu__item"><a class="menu__link" href="<?=$product['url_main']?>" target="_blank">Main website</a></li>
				</ul>
				<div class="langs top__block"><a class="langs__link langs__link--current" href="/<?=$lng['language_code']?>/"><?=$lng['language_code']?></a>
					<div class="langs__hidden"><a class="langs__link<?php if($lng['language_code']=='en'){echo' langs__link--active';}?>" href="/en/">en</a><a class="langs__link<?php if($lng['language_code']=='ru'){echo' langs__link--active';}?>" href="/ru/">ru</a><a class="langs__link<?php if($lng['language_code']=='ja'){echo' langs__link--active';}?>" href="/ja/">ja</a><a class="langs__link<?php if($lng['language_code']=='ko'){echo' langs__link--active';}?>" href="/ko/">ko</a><a class="langs__link<?php if($lng['language_code']=='cn'){echo' langs__link--active';}?>" href="/cn/">cn</a></div>
				</div>
				<div class="top__copy"><?=$product['footer_copyright']?> <img src="/assets/products/<?=$product['id']?>/logo_small@2x.png" width="69" alt="" /></div>
			</div>
			<a class="logo" href="<?=$product['url_main']?>" target="_blank"><img src="/assets/products/<?=$product['id']?>/logo.png" srcset="/assets/products/<?=$product['id']?>/logo@2x.png 2x" alt="" /></a>
		</div>
	</nav>
<?php
if($module=='signin'){
?>
	<div class="wrapper">
		<div class="container">
			<form class="form form--portal" id="signin-form" method="post" action="" novalidate="novalidate">
			<input type="hidden" name="do" value="signin">
				<div class="form__content">
					<div class="form__title"><span><?=$lng['auth_signin']?></span></div>
					<div class="message message--error message--form" id="signin-error" style="display: none;"></div>
					<div class="form__group">
						<label class="form__label"><?=$lng['label_email']?></label>
						<input class="form__input" name="email" placeholder="<?=$lng['field_email']?>" type="email" />
						<span id="signin-email-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['label_password']?></label>
						<input class="form__input" name="password" placeholder="<?=$lng['field_password']?>" type="password" />
						<span id="signin-password-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="captcha" style="text-align: center;">
<ul class="scl-nav">
<?php if($product['twitter_consumer_key']!='' && $product['twitter_consumer_secret']!=''){echo'  <li><a href="/auth/twitter/" class="twi"><i class="fa fa-twitter"></i></a></li>';}?>
<?php if($product['facebook_app_id']!='' && $product['facebook_app_secret']!=''){echo'  <li><a href="/auth/facebook/" class="fc"><i class="fa fa-facebook"></i></a></li>';}?>
<?php if($product['google_oauth']=='1'){echo'  <li><a href="/auth/google/" class="g-p"><i class="fa fa-google-plus"></i></a></li>';}?>
</ul>
					</div>
					<div class="text--center">
						<div class="m-spinner m-spinner--auth m-spinner--lg" id="loading" style="display: none;"></div>
						<button type="button" id="signin-button" class="btn"><?=$lng['auth_button_signin']?></button>
					</div>
					<div class="form__links"><a class="form__link form__link--grey" href="/signup/"><?=$lng['auth_signup_link']?></a> <?=$lng['auth_or']?> <a class="form__link form__link--grey" href="/reset/"><?=$lng['auth_reset_link']?></a>
					</div>
				</div>
			</form>
		</div>
	</div>
<?php }
if($module=='signup'){
?>
<style type="text/css">
    .grecaptcha-badge{
        display:none;
    }
</style>
<script src="/assets/js/jquery-3.2.1.min.js"></script>
<script type="text/javascript">


function onSubmit() {
/* Sign up */
	
//    e.preventDefault();
//    e.stopImmediatePropagation();

var data = $('#signup-form').serialize();

$.ajax({
    type: 'POST',
    url: '/auth/',
    data: data,
    beforeSend: function() {
        $('#signup-button').prop('disabled', true);
		$('#signup-button').hide();
		$('#loading').show();
        $('#signup-email-error').hide();
        $('#signup-ethereum-error').hide();
        $('#signup-password-error').hide();
        $('#signup-password_confirmation-error').hide();
        $('#signup-captcha-error').hide();
		$('#signup-agree').css('color','#1c1c1c').css('font-weight','normal');
		$('#signup-error').hide();
    },
    success: function(result) {
		
        if (result.status == 'success' || result.status == 'signed_in') {document.location.reload(true);return true;}
		
		grecaptcha.reset();
		
        $('#signup-button').prop('disabled', false);
		$('#signup-button').show();
		$('#loading').hide();
		
        if (result.status == 'error') {
		$('#signup-error').html(result.message);
		$('#signup-error').show();
		}
        if (result.status == 'agree') {
		$('#signup-agree').css('color','#d23').css('font-weight','bold');
		}
        if (result.status == 'activation') {
		$('#signup-fields').hide();
		$('#signup-success').html(result.message);
		$('#signup-success').show();
		$('#activation-resend').bind( "click", {  foo: "bar"}, activation_resend );
		}
        if (result.status == 'email') {
            $('#signup-email-error').html(result.message);
            $('#signup-email-error').show();
        }
        if (result.status == 'ethereum') {
            $('#signup-ethereum-error').html(result.message);
            $('#signup-ethereum-error').show();
        }
        if (result.status == 'password') {
            $('#signup-password-error').html(result.message);
            $('#signup-password-error').show();
        }
        if (result.status == 'password_confirmation') {
            $('#signup-password_confirmation-error').html(result.message);
            $('#signup-password_confirmation-error').show();
        }
        if (result.status == 'captcha') {
            $('#signup-captcha-error').html(result.message);
            $('#signup-captcha-error').show();
            $('#captcha').val('');
			$('#captcha-image').attr('src', $('#captcha-image').attr('src')+'?'+Math.random());
        }

        console.log(result);
    },
    error: function() {
        console.log('Cannot retrieve data.');
    }
});
return false;

};

/* Resend activation */
function activation_resend( e ) {
	
    e.preventDefault();
    e.stopImmediatePropagation();

$.ajax({
    type: 'POST',
    url: '/auth/',
    headers: {
      'Content-Type':'application/x-www-form-urlencoded'
    },
    data: {
      'do': 'activation_resend',
      'user_id': $('#activation-resend').data('user_id')
    },
    beforeSend: function() {
		$('#activation-resend').hide();
    },
    success: function(result) {
        if (result.status == 'error') {return true;}
		
        if (result.status == 'time') {
		setTimeout(function() {$('#activation-resend').show();}, result.message);
		}		
        console.log(result);
    },
    error: function() {
        console.log('Cannot retrieve data.');
    }
});
return false;
};
</script>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
	<div class="wrapper">
		<div class="container">
			<form class="form form--portal" id="signup-form" method="post" action="" novalidate="novalidate">
			<input type="hidden" name="do" value="signup">
				<div class="form__content">
					<div class="form__title"><span><?=$lng['auth_signup']?></span></div>
					<div class="message message--error message--form" id="signup-error" style="display: none;"></div>
					<div class="message message--success message--form" id="signup-success" style="display: none;"></div>
					<span id="signup-fields">
					<div class="captcha" style="text-align: center;">
<ul class="scl-nav">
<?php if($product['twitter_consumer_key']!='' && $product['twitter_consumer_secret']!=''){echo'  <li><a href="/auth/twitter/" class="twi"><i class="fa fa-twitter"></i></a></li>';}?>
<?php if($product['facebook_app_id']!='' && $product['facebook_app_secret']!=''){echo'  <li><a href="/auth/facebook/" class="fc"><i class="fa fa-facebook"></i></a></li>';}?>
<?php if($product['google_oauth']=='1'){echo'  <li><a href="/auth/google/" class="g-p"><i class="fa fa-google-plus"></i></a></li>';}?>
</ul>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['label_email']?></label>
						<input class="form__input" name="email" placeholder="<?=$lng['field_email']?>" type="email" />
						<span id="signup-email-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['label_password']?></label>
						<input class="form__input" name="password" id="signup-password" placeholder="<?=$lng['field_password']?>" type="password" />
						<span id="signup-password-error" class="form__error" style="display: none;"></span>
						<span class="password-strength" id="password-strength"><?=$lng['password_strength']?>: <?=$lng['password_weak']?></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['label_password_confirmation']?></label>
						<input class="form__input" name="password_confirmation" placeholder="<?=$lng['field_password_confirmation']?>" type="password" />
						
						<p style="text-align:center; padding-top: 15px;">
						    <input type="checkbox" id="res_check" name="subscribe" value="newsletter">
    <label for="res_check" style="padding-top:15px;">I confirm I am not a resident of the United States, China or Singapore.</label>
						    </p>

<script>

$(function() {
  // Handler for .ready() called.
  var chkbox = $("#res_check");
                button = $("#signup-button");
           button.attr("disabled","disabled");
           
            chkbox.change(function(){
                if(this.checked){
                    button.removeAttr("disabled");
                    //alert("confirmed");
                }else{
                    button.attr("disabled","disabled");
                }
            });
});

</script>
						<span id="signup-password_confirmation-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<!--<label class="form__label"><?=$lng['field_ethereum_address']?><?php if($product['ethereum_field_required']=='0'){echo ' '.$lng['recommended'];}?></label>
						<input class="form__input" name="ethereum" placeholder="<?=$lng['field_ethereum_address']?>" type="text" />-->
						<span id="signup-ethereum-error" class="form__error" style="display: none;"></span>
					</div>
					<?php if($product['captcha_enabled']=='1'){?><div class="form__group">
						<label class="form__label"><?=$lng['field_captcha']?></label>
						<div class="captcha" style="text-align: center;margin: 0 auto 20px;"><img src="/captcha/" id="captcha-image" style="cursor: pointer;"></div>
						<input class="form__input" name="captcha" id="captcha" placeholder="<?=$lng['field_captcha']?>" type="text" />
						<span id="signup-captcha-error" class="form__error" style="display: none;"></span>
					</div>
					<?php }if($product['user_agreement']!=''){?><label class="form__checkbox form__checkbox--agree" id="signup-agree"><input type="checkbox" name="agree" value="1"/><span></span><?=$lng['checkbox_agree']?><a class="form__link" href="<?=$product['user_agreement']?>" target="_blank"><?=$lng['user_agreement']?></a></label><?php }?>
					<div class="text--center">
						<div class="m-spinner m-spinner--auth m-spinner--lg" id="loading" style="display: none;"></div>
						<button type="submit" id="signup-button" class="btn g-recaptcha" data-sitekey="<?=$product['recaptcha_site_key']?>" data-callback="onSubmit"><?=$lng['auth_button_signup']?></button>
					</div>
					<div class="form__links"><?=$lng['auth_or']?> <a class="form__link form__link--grey" href="/signin/"><?=$lng['auth_signin_link']?></a>
					</div>
					</span>
				</div>
			</form>
		</div>
	</div>
<?php }

// two factor code here:

if($module=='two_factor'){

require_once 'modules/auth/GoogleAuthenticator.php';
$ga=new GoogleAuthenticator();
?>
	<div class="wrapper">
		<div class="container">
			<form class="form form--portal" id="two_factor-form" method="post" action="" novalidate="novalidate">
			<input type="hidden" name="do" value="two_factor">
				<div class="form__content">
					<div class="form__title"><span><?=$lng['auth_two_factor']?></span></div>
					<div class="message message--error message--form" id="two_factor-error" style="display: none;"></div>					
					<div class="form__group text--center">
						<label class="form__label"><?=$lng['two_factor_caption']?></label>
						<input class="form__input text--center" name="two_factor_code" id="two_factor_code" placeholder="------"  maxlength="6" autofocus="true" style="display:inline-block;border: 1px solid #e5e5e5;border-radius: 2px;-webkit-box-shadow: none;box-shadow: none;width: 180px;padding: 14px 0px 14px 0px;">
					</div>
					<div class="text--center">
						<div class="m-spinner m-spinner--auth m-spinner--lg" id="loading" style="display: none;"></div>
						<button type="button" id="two_factor-button" class="btn"><?=$lng['auth_button_signin']?></button>
					</div>
					<div class="form__links"><?=$lng['auth_or']?> <a class="form__link form__link--grey" href="/signout/<?=$_SESSION['authID']?>/"><?=$lng['auth_signout']?></a>
					</div>
				</div>
			</form>
		</div>
	</div>
<?php }  ?>		
<?php 
if($module=='reset'){
?>
	<div class="wrapper">
		<div class="container">
			<form class="form form--portal" id="reset-form" method="post" action="" novalidate="novalidate">
			<input type="hidden" name="do" value="reset">
				<div class="form__content">
					<div class="form__title"><span><?=$lng['auth_reset']?></span></div>
					<div class="message message--error message--form" id="reset-error" style="display: none;"></div>
					<span id="reset-fields">
					<div class="form__group">
						<label class="form__label"><?=$lng['label_email']?></label>
						<input class="form__input" name="email" placeholder="<?=$lng['field_email']?>" type="email" />
						<span id="reset-email-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="captcha"></div>
					<div class="text--center">
						<div class="m-spinner m-spinner--auth m-spinner--lg" id="loading" style="display: none;"></div>
						<button type="button" id="reset-button" class="btn"><?=$lng['auth_button_reset']?></button>
					</div>
					</span>
					<div class="message message--success message--form" id="reset-success" style="display: none;"></div>
					<div class="form__links"><?=$lng['auth_or']?> <a class="form__link form__link--grey" href="/signin/"><?=$lng['auth_signin_link']?></a>
					</div>
				</div>
			</form>
		</div>
	</div>
	


<?php }
if($module=='signup_code'){
?>
	<div class="wrapper">
		<div class="container">
			<form class="form form--portal" novalidate="novalidate">
				<div class="form__content">
					<div class="form__title"><span><?=$lng['auth_signup']?></span></div>
					<div class="message message--error message--form"><?=$activation['message']?></div>
					<div class="form__links"><?=$lng['auth_or']?> <a class="form__link form__link--grey" href="/signin/"><?=$lng['auth_signin_link']?></a>
				</div>
			</form>
		</div>
	</div>
<?php }
if($module=='reset_code'){

$request_get=$auth->request_get($code,$request_id,'reset');
?>
	<div class="wrapper">
		<div class="container">
			<form class="form form--portal" id="reset_code-form" method="post" action="" novalidate="novalidate">
			<input type="hidden" name="do" value="reset_code">
				<div class="form__content">
					<div class="form__title"><span><?=$lng['auth_reset']?></span></div>
					<div class="message message--error message--form" id="reset_code-error" style="display: none;"></div>
<?php
if($request_get['status']!='success'){echo'<div class="message message--error message--form">'.$request_get['message'].'</div><div class="form__links">'.$lng['auth_or'].' <a class="form__link form__link--grey" href="/signin/">'.$lng['auth_signin_link'].'</a>';}else{
?>
					<input type="hidden" name="code" value="<?=$code?>">
					<input type="hidden" name="request_id" value="<?=$request_id?>">
					<div class="form__group">
						<label class="form__label"><?=$lng['new_password_label']?></label>
						<input class="form__input" name="new_password" id="new_password" placeholder="<?=$lng['field_new_password']?>" type="password" />
						<span class="password-strength" id="password-strength"><?=$lng['password_strength']?>: <?=$lng['password_weak']?></span>
						<span id="reset_code-new_password-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['new_password_confirmation_label']?></label>
						<input class="form__input" name="new_password_confirmation" placeholder="<?=$lng['field_new_password_confirmation']?>" type="password" />
						<span id="reset_code-new_password_confirmation-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="text--center">
						<div class="m-spinner m-spinner--auth m-spinner--lg" id="loading" style="display: none;"></div>
						<button type="button" id="reset_code-button" class="btn"><?=$lng['button_save']?></button>
					</div>
<?php }?>
				</div>
			</form>
		</div>
	</div>
<?php }

require 'modules/footer.php';
?>
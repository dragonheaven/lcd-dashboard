<?php
if(!isset($settings) || $module!='two_factor' || !$logged_in['flag']){header('location: /');exit;}

require_once 'modules/auth/GoogleAuthenticator.php';
$ga = new GoogleAuthenticator();

if(!isset($_SESSION['two_factor_secret'])){$_SESSION['two_factor_secret']=$ga->createSecret();}
?>	<div class="container">
		<form class="form" id="two_factor_settings-form" method="post" action="" novalidate="novalidate">
		<input type="hidden" name="do" value="two_factor_settings">
			<div class="form__content">
				<div class="form__title"><span><?=$lng['auth_two_factor']?></span></div>
					<div class="message message--error message--form" id="two_factor_settings-error" style="display: none;"></div>
					<div class="message message--success message--form" id="two_factor_settings-success" style="display: none;"></div>
		<?php if($user['two_factor']!=''){?>
		<input type="hidden" name="act" value="disable">
				<div class="text--center">
					<div class="m-spinner m-spinner--auth m-spinner--lg" id="loading" style="display: none;"></div>
					<button class="btn" type="button" id="two_factor_settings-button"><?=$lng['button_two_factor_disable']?></button>
				</div>
		<?php }else{?>
		<input type="hidden" name="act" value="enable">
					<span id="two_factor_settings-fields">
					<?=$lng['two_factor_instruction']?>
					<div class="qr">
						<img id="deposit_qr" src="<?=$ga->getQRCodeUrl(str_replace(' ','-',$product['name']),$_SESSION['two_factor_secret'])?>" width="180" alt="" />
						<p><?=$lng['two_factor_manual']?></p><p><b><?=$_SESSION['two_factor_secret']?></b></p>
					</div>
					<div class="form__group text--center">
						<label class="form__label"><?=$lng['two_factor_caption']?></label>
						<input class="form__input text--center" name="two_factor_code" id="two_factor_code" placeholder="------"  maxlength="6" autofocus="true" style="display:inline-block;border: 1px solid #e5e5e5;border-radius: 2px;-webkit-box-shadow: none;box-shadow: none;width: 180px;padding: 14px 0px 14px 0px;">
					</div>
				<div class="text--center">
					<div class="m-spinner m-spinner--auth m-spinner--lg" id="loading" style="display: none;"></div>
					<button class="btn" type="button" id="two_factor_settings-button"><?=$lng['button_two_factor_enable']?></button>
				</div>
					</span>
		<?php }?>
			</div>
		</form>
	</div>
<?php require 'modules/footer.php';?>
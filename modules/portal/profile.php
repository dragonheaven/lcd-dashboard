<?php
if(!isset($settings) || $module!='profile' || !$logged_in['flag']){header('location: /');exit;}
?>	<div class="container">
		<form class="form" id="profile-form" method="post" action="" novalidate="novalidate">
		<input type="hidden" name="do" value="profile">
		<input type="hidden" name="pw" value="0">
			<div class="form__content">
				<div class="form__title"><span><?=$lng['menu_settings']?></span></div>
					<div class="message message--error message--form" id="profile-error" style="display: none;"></div>
					<div class="message message--success message--form" id="profile-success" style="display: none;"></div>
				<div class="form__group">
						<div class="form__row form__full form__row--grey">
							<div class="row">
								<div class="col-sm-8">
									<div class="form__text form__text--grey"><?=$lng['auth_two_factor']?> <?php if($user['two_factor']!=''){echo $lng['two_factor_enabled'];}else{echo $lng['two_factor_disabled'];}?></div>
								</div>
								<div class="col-sm-4 form__right">
									<a class="btn btn--sm btn--transparent btn--copy" href="/two_factor/"><span><?=$lng['button_two_factor']?></span>
									</a>
								</div>
							</div>
						</div>
				</div>
				<div class="form__group">
					<label class="form__label">Email</label>
					<input class="form__input" value="<?=$user['email']?>" disabled="disabled" />
				</div>
				<div class="form__group">
					<label class="form__label"><?=$lng['ethereum_address_label']?></label>
					<input class="form__input" name="ethereum" value="<?=$user['ethereum_address']?>" placeholder="<?=$lng['field_ethereum_address']?>" />
					<span id="profile-ethereum-error" class="form__error" style="display: none;"></span>
				</div>
				<div class="form__group">
					<label class="form__checkbox"><input class="js-change-password" type="checkbox" /><span> </span><?=$lng['change_password']?></label>
				</div>
				<div class="form__password">
					<div class="well__title"><?=$lng['change_password']?></div>
					<div class="form__group">
						<label class="form__label"><?=$lng['current_password_label']?></label>
						<input class="form__input" name="current_password" id="current_password" placeholder="<?=$lng['field_current_password']?>" type="password" />
						<span id="profile-current_password-error" class="form__error" style="display: none;"></span>
						<span class="password-strength" id="password-strength"><?=$lng['password_strength']?>: <?=$lng['password_weak']?></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['new_password_label']?></label>
						<input class="form__input" name="new_password" placeholder="<?=$lng['field_new_password']?>" type="password" />
						<span id="profile-new_password-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['new_password_confirmation_label']?></label>
						<input class="form__input" name="new_password_confirmation" placeholder="<?=$lng['field_new_password_confirmation']?>" type="password" />
						<span id="profile-new_password_confirmation-error" class="form__error" style="display: none;"></span>
					</div>
				</div>
				<div class="text--center">
					<div class="m-spinner m-spinner--auth m-spinner--lg" id="loading" style="display: none;"></div>
					<button class="btn" type="button" id="profile-button"><?=$lng['button_save']?></button>
				</div>
			</div>
		</form>
	</div>
<?php require 'modules/footer.php';?>
<?php
if(!isset($settings) || $module!='support' || !$logged_in['flag']){header('location: /');exit;}
?>
	<div class="container">
		<form class="form" id="support-form" method="post" action="" novalidate="novalidate">
		<input type="hidden" name="do" value="support">
			<div class="form__content">
				<div class="form__title"><span><?=$lng['support_title']?></span></div>
				<div class="message message--success message--form" id="support-success" style="display: none;"></div>
				<div class="message message--error message--form" id="support-error" style="display: none;"></div>
				<div class="form__group">
					<label class="form__label"><?=$lng['support_subject']?></label>
					<div class="select">
						<select class="form__input" name="subject">
							<option value="1"><?=$lng['support_payments']?></option>
							<option value="2"><?=$lng['support_tech']?></option>
						</select>
					</div>
				</div>
				<div class="form__group">
					<label class="form__label"><?=$lng['support_message']?></label>
					<textarea class="form__input form__input--textarea" name="message"></textarea>
				</div>
				<div class="text--center">
					<div class="m-spinner m-spinner--auth m-spinner--lg" id="loading" style="display: none;"></div>
					<button class="btn btn--buy" type="button" id="support-button"><?=$lng['support_send_message']?></button>
				</div>
			</div>
		</form>
	</div>
<?php require 'modules/footer.php';?>
<?php
if(!isset($settings) || $module!='buy' || !$logged_in['flag']){header('location: /');exit;}
?>
	<form class="form form--portal-big form--lg" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
		<div class="form__content">

			<div class="form__title form__title--2" style="text-align: center">
			  <p><strong><u>DASHBOARD UNDER MAINTENANCE</u>
		      <!--<li class="tabs__item"><a class="tabs__link" href="#card" data-toggle="tab"><?=$lng['payment_card']?></a></li>-->			</strong></p>
			  <p>&nbsp;</p>
			  <p>Welcome to Lucyd. To get LCD tokens, please message support on <a href="https://t.me/harrisongross" target="new">Telegram</a> or email <a href="mailto:info@lucyd.co">info@lucyd.co</a>.</p>
			  <p>&nbsp;</p>
			  <p>Please verify addresses on <a href="https://clearify.io" target="new">clearify.io</a> before sending. Staff will not send you unsolicited PMs or emails. Thank you.</p>
			</div>
<div class="tab-content">
				<div class="tab-pane js-calc-tab fade in active" id="alts">
					<div class="form__group"></div>
					<div class="form__group"></div>
				</div>
				<div class="tab-pane js-calc-tab" id="exchange">
				</div>
				<!--<div class="tab-pane js-calc-tab" id="paypal">
				    <p align="center" style="padding-top: 10px;">Paypal contributions may take up to 24 hours to show in the dashboard.</p>
					<div class="form__group"></div>
					<div class="text--center">
					<div class="select">
<select class="form__input" name="os0">
<option value="600 LCD tokens">600 LCD tokens $150.00 USD</option>
  
<option value="2400 LCD tokens+10% Bonus">2400 LCD tokens+10% Bonus $600.00 USD</option>

<option value="4800 LCD tokens+10% Bonus+Automatic Beta Lucyd Lens preorder">4800 LCD tokens+10% Bonus+Automatic Beta Lucyd Lens preorder $1,200.00 USD</option>

<option value="12000 LCD tokens+15% Bonus+Automatic Beta Lucyd Lens preorder">12000 LCD tokens+15% Bonus+Automatic Beta Lucyd Lens preorder $3,000.00 USD</option>

<option value="24000 LCD tokens+20% Bonus+Automatic Beta Lucyd Lens preorder">24000 LCD tokens+20% Bonus+Automatic Beta Lucyd Lens preorder $6,000.00 USD</option>

<option value="36000 LCD tokens+25% Bonus+Automatic Beta Lucyd Lens preorder">36000 LCD tokens+25% Bonus+Automatic Beta Lucyd Lens preorder $9,000.00 USD</option>

<option value="72000 LCD tokens+35% Bonus+Automatic Beta Lucyd Lens preorder">72000 LCD tokens+35% Bonus+Automatic Beta Lucyd Lens preorder $18,000.00 USD</option>
</select>

					</div>
<input name="cmd" type="hidden" value="_s-xclick" />
<input name="hosted_button_id" type="hidden" value="VNQHH7GPXFF6W" />
<input name="on0" type="hidden" value="<?=$product['token_name']?> Token Packages" />
<input name="on1" type="hidden" value="User ID and Email:" />
<input name="os1" type="hidden" value="<?=$user['id']?>|<?=$user['email']?>"/>
<input name="currency_code" type="hidden" value="USD" />
<p><input alt="PayPal – The safer, easier way to pay online!" name="submit" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_buynowCC_LG.gif" type="image" /><br>
<img src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" alt="" width="1" height="1" border="0" /></p>
					</div>
				</div>
			</div>
		</div>
	</form>-->
<?php require 'modules/footer.php';?>
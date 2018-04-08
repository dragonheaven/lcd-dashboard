<?php
if(!isset($settings) || $module!='buy' || !$logged_in['flag']){header('location: /');exit;}
?>
	<form class="form form--portal-big form--lg" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
		<div class="form__content">

			<div class="form__title form__title--2"><?=$lng['purchase_title']?>
			  <p>&nbsp;</p>
			  <p>If no ETH address appears, please contact <a href="mailto:info@lucyd.co">info@lucyd.co</a> or <a href="https://t.me/harrisongross" target="new">@harrisongross</a> on Telegram for manual payment.&nbsp;</p>
</div>
			<ul class="tabs js-calc-tabs">
				<li class="tabs__item active"><a class="tabs__link" href="#alts" data-toggle="tab"><?=$lng['payment_cryptocurrency']?></a></li>
				<li class="tabs__item"><a class="tabs__link" href="#exchange" data-toggle="tab"><?=$lng['payment_exchange']?></a></li>-->
				<!--<li class="tabs__item"><a class="tabs__link" href="#card" data-toggle="tab"><?=$lng['payment_card']?></a></li>-->
				<!--<li class="tabs__item"><a class="tabs__link" href="#paypal" data-toggle="tab"><?=$lng['payment_paypal']?></a></li>-->
			</ul>
			<div class="tab-content">
				<div class="tab-pane js-calc-tab fade in active" id="alts">
					<div class="form__group"></div>
					<div class="form__group">
						<label class="form__label"><br />
					    <?=$lng['deposit_choose']?></label>
						<div class="select">
						<select class="form__input" id="currency" name="currency"><option value=""><?=$lng['deposit_choose']?></option><?php foreach($currency_settings as $letters=>$enabled){if($enabled=='1'){echo'<option value="'.$letters.'">'.$currency_names[$letters].'</option>';}}?></select>
						</div>
					</div>
					<div class="text--center">
					<div class="m-spinner m-spinner--auth m-spinner--lg" id="loading" style="display: none;"></div>
					</div>
					<div id="invest-userwallets" style="display: none;">
					<div class="form__group">
						<label class="form__label"><?=$lng['userwallet_title']?></label>
						<input class="form__input form__input--address" id="userwallet_address" value="" placeholder="<?=$lng['userwallet_placeholder']?>" />
						<span id="userwallet_address-error" class="form__error" style="display: none;"></span>
						<button class="js-copy btn btn--sm btn--transparent btn--copy btn--ininput" id="userwallet_address-button"> <span><?=$lng['userwallet_button']?></span>
						</button>
						<input type="hidden" id="SBD" value="<?=$user['SBD_address']?>">
						<input type="hidden" id="GOLOS" value="<?=$user['GOLOS_address']?>">
						<input type="hidden" id="BTS" value="<?=$user['BTS_address']?>">
					</div>
					</div>
					<div id="invest-purchase" style="display: none;">
					    
				      <script type="text/javascript">
					            $('#currency_calc').bind('keypress keydown keyup', function(e){
       if(e.keyCode == 13) { e.preventDefault(); }
    });
					    </script>
					    
					<div class="form__full form__row--grey calc">
						<div class="calc__text text"><?=$lng['deposit_amount']?><b class="red" id="currency_name_amount"></b></div>
						<span class="calc__control calc__control--minus js-calc-minus"></span>
						<input class="calc__input js-calc-input" placeholder="<?=$lng['deposit_enter_amount']?>" data-step="0.01" data-currency="0" autofocus="autofocus" id="currency_calc" type="number" />
						<input type="hidden" id="calc_price" value="0">
						<span class="calc__control calc__control--plus js-calc-plus"></span>
						<div class="calc__text__bottom text">1 <span id="currency_name_usd"></span> = <span id="currency_to_usd"></span><?php if($product['token_price_eth']!='0' && $product['token_price_eth']>0){echo' ETH';}else{echo'$';}?></div>
					</div>
					<div class="form__black form__full black-data">
						<div class="black-data__text"><?=$lng['deposit_return']?></div>
						<div class="black-data__value js-calc-portal" id="calc_result">0</div>
						<div class="calc__text__bottom text" style="color: #fff; margin-top: 0px;"><b>1 <?=$product['token_name']?> = <span id="token_price"></span><?php if($product['token_price_eth']!='0' && $product['token_price_eth']>0){echo' ETH';}else{echo'$';}?></b></div>
						<img class="black-data__logo" src="/assets/products/<?=$product['id']?>/logo_white.png" srcset="/assets/products/<?=$product['id']?>/logo_white@2x.png 2x" alt="" />
					</div>
					<div class="qr">
						<img id="deposit_qr" src="https://chart.googleapis.com/chart?chs=225x225&chld=L|2&cht=qr&chl=" width="180" alt="" />
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['deposit_address']?></label>
						<input class="form__input form__input--address" id="deposit_address" value="" onclick="this.select();" readonly="readonly" />
						<button class="js-copy btn btn--sm btn--transparent btn--copy btn--ininput" id="deposit_address_clipboard" data-clipboard-text=""> <span><?=$lng['button_copy_wallet']?></span>
						</button>
					</div>
					<div class="form__footer"><?=$lng['deposit_description']?></div>
					</div>
				</div>
				<div class="tab-pane js-calc-tab" id="exchange">
					<div class="form__group"></div>
					<div class="text--center">
<?php
#Получить адрес кошелька Ethereum через Coinpayments API для покупки токенов через Changelly
$currency = 'ETH';

$query = $db->prepare("SELECT wallet_address FROM deposit_wallets WHERE uid = ? AND currency = ? AND product_id = ?");
$query->execute(array($user['id'],$currency,$product['id']));

if($query->rowCount()>0){$data = $query->fetch(\PDO::FETCH_ASSOC);$eth_address=$data['wallet_address'];}else{

$deposit_address=getCoinPaymentsWalletAddress($currency,$product['coinpayments_public_key'],$product['coinpayments_private_key']);

$query = $db->prepare("INSERT INTO deposit_wallets (uid, currency, wallet_address, product_id, time) VALUES (?, ?, ?, ?, ?)");
if($query->execute(array($user['id'],$currency,$deposit_address,$product['id'],time()))){$eth_address=$deposit_address;}
}
?>
<iframe src="https://changelly.com/widget/v1?auth=email&from=LTC&to=ETH&merchant_id=cbfd025e6b50&address=<?=$eth_address?>&amount=1&ref_id=cbfd025e6b50&color=583ffc" width="600" height="500" class="changelly" scrolling="no" style="overflow-y: hidden; border: none"></iframe>
					</div>
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
<?php
if(!isset($settings) || $module!='admin' || !$logged_in['flag'] || $user['admin']!='1'){header('location: /');exit;}
?>
<style type="text/css">
body {
  overflow-x: auto !important;
}
</style>

	<div class="form form--portal-big form--lg" style="max-width:none;">
		<div class="form__content">
			<div class="form__title form__title--2"><?=$lng['admin_panel']?></div>
					<div class="message message--error message--form" id="admin-error" style="display: none;"></div>
					<div class="message message--success message--form" id="admin-success" style="display: none;"></div>
			<ul class="tabs js-calc-tabs">
				<li class="tabs__item active"><a class="tabs__link" href="#main" data-toggle="tab"><?=$lng['admin_tab_main']?></a></li>
				<li class="tabs__item"><a class="tabs__link" href="#settings" data-toggle="tab"><?=$lng['admin_tab_settings']?></a></li>
				<!--<li class="tabs__item"><a class="tabs__link" href="#payments" data-toggle="tab"><?=$lng['admin_tab_payments']?></a></li>-->
				<li class="tabs__item"><a class="tabs__link" href="#users" data-toggle="tab"><?=$lng['admin_tab_users']?></a></li>
				<li class="tabs__item"><a class="tabs__link" href="#tokens" data-toggle="tab"><?=$lng['admin_tab_tokens']?></a></li>
				<li class="tabs__item"><a class="tabs__link" href="#support" data-toggle="tab"><?=$lng['admin_tab_support']?></a></li>
				<li class="tabs__item"><a class="tabs__link" href="#stats" data-toggle="tab"><?=$lng['admin_tab_stats']?></a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane js-calc-tab fade in active" id="main">
				<form class="form" id="admin_main-form" method="post" action="" novalidate="novalidate">
				<input type="hidden" name="do" value="admin_main">
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_name']?></label>
						<input class="form__input" name="name" value="<?=$product['name']?>" placeholder="<?=$lng['admin_name']?>" />
						<span id="admin-name-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_short_name']?></label>
						<input class="form__input" name="short_name" value="<?=$product['short_name']?>" placeholder="<?=$lng['admin_short_name']?>" />
						<span id="admin-short_name-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_url']?></label>
						<input class="form__input" name="url" value="<?=$product['url']?>" placeholder="<?=$lng['admin_placeholder_url']?>" />
						<span id="admin-url-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_url_main']?></label>
						<input class="form__input" name="url_main" value="<?=$product['url_main']?>" placeholder="<?=$lng['admin_placeholder_url']?>" />
						<span id="admin-url_main-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_user_agreement']?></label>
						<input class="form__input" name="user_agreement" value="<?=$product['user_agreement']?>" placeholder="<?=$lng['admin_placeholder_url']?>" />
						<span id="admin-user_agreement-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_footer_copyright']?></label>
						<input class="form__input" name="footer_copyright" value="<?=$product['footer_copyright']?>" placeholder="<?=$lng['admin_footer_copyright']?>" />
						<span id="admin-footer_copyright-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_token_name']?></label>
						<input class="form__input" name="token_name" value="<?=$product['token_name']?>" placeholder="<?=$lng['admin_token_name']?>" />
						<span id="admin-token_name-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_smtp_host']?></label>
						<input class="form__input" name="smtp_host" value="<?=$product['smtp_host']?>" placeholder="<?=$lng['admin_smtp_host']?>" />
						<span id="admin-smtp_host-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_email']?></label>
						<input class="form__input" name="email" value="<?=$product['email']?>" placeholder="<?=$lng['admin_placeholder_email']?>" />
						<span id="admin-email-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_smtp_password']?></label>
						<input class="form__input" name="smtp_password" value="<?=$product['smtp_password']?>" placeholder="<?=$lng['admin_smtp_password']?>" />
						<span id="admin-smtp_password-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_smtp_security']?></label>
						<div class="select"><select class="form__input" name="smtp_security"><option value="ssl"<?php if($product['smtp_security']=='ssl'){echo' selected="selected"';}?>>SSL</option><option value="tls"<?php if($product['smtp_security']=='tls'){echo' selected="selected"';}?>>TLS</option></select></div>
						<span id="admin-smtp_security-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_smtp_port']?></label>
						<input class="form__input" name="smtp_port" value="<?=$product['smtp_port']?>" placeholder="<?=$lng['admin_smtp_port']?>" />
						<span id="admin-smtp_port-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_smartcontract_address']?></label>
						<input class="form__input" name="smartcontract_address" value="<?=$product['smartcontract_address']?>" placeholder="<?=$lng['admin_placeholder_ethereum']?>" />
						<span id="admin-smartcontract_address-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_smartcontract_creator']?></label>
						<input class="form__input" name="smartcontract_creator" value="<?=$product['smartcontract_creator']?>" placeholder="<?=$lng['admin_placeholder_ethereum']?>" />
						<span id="admin-smartcontract_creator-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_coinpayments_public_key']?></label>
						<input class="form__input" name="coinpayments_public_key" value="<?=$product['coinpayments_public_key']?>" placeholder="<?=$lng['admin_coinpayments_public_key']?>" />
						<span id="admin-coinpayments_public_key-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_coinpayments_private_key']?></label>
						<input class="form__input" name="coinpayments_private_key" value="<?=$product['coinpayments_private_key']?>" placeholder="<?=$lng['admin_coinpayments_private_key']?>" />
						<span id="admin-coinpayments_private_key-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_coinpayments_ipn_secret']?></label>
						<input class="form__input" name="coinpayments_ipn_secret" value="<?=$product['coinpayments_ipn_secret']?>" placeholder="<?=$lng['admin_coinpayments_ipn_secret']?>" />
						<span id="admin-coinpayments_ipn_secret-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_coinpayments_merchant_id']?></label>
						<input class="form__input" name="coinpayments_merchant_id" value="<?=$product['coinpayments_merchant_id']?>" placeholder="<?=$lng['admin_coinpayments_merchant_id']?>" />
						<span id="admin-coinpayments_merchant_id-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_etherscan_api_key']?></label>
						<input class="form__input" name="etherscan_api_key" value="<?=$product['etherscan_api_key']?>" placeholder="<?=$lng['admin_etherscan_api_key']?>" />
						<span id="admin-etherscan_api_key-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_twitter_consumer_key']?></label>
						<input class="form__input" name="twitter_consumer_key" value="<?=$product['twitter_consumer_key']?>" placeholder="<?=$lng['admin_twitter_consumer_key']?>" />
						<span id="admin-twitter_consumer_key-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_twitter_consumer_secret']?></label>
						<input class="form__input" name="twitter_consumer_secret" value="<?=$product['twitter_consumer_secret']?>" placeholder="<?=$lng['admin_twitter_consumer_secret']?>" />
						<span id="admin-twitter_consumer_secret-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_facebook_app_id']?></label>
						<input class="form__input" name="facebook_app_id" value="<?=$product['facebook_app_id']?>" placeholder="<?=$lng['admin_facebook_app_id']?>" />
						<span id="admin-facebook_app_id-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_facebook_app_secret']?></label>
						<input class="form__input" name="facebook_app_secret" value="<?=$product['facebook_app_secret']?>" placeholder="<?=$lng['admin_facebook_app_secret']?>" />
						<span id="admin-facebook_app_secret-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"></label>
						<label class="form__checkbox" style="text-align: start;"><input type="checkbox" name="google_oauth" value="1"<?php if($product['google_oauth']=='1'){echo' checked="checked"';}?>><span></span><?=$lng['admin_google_oauth']?></label>
						<span id="admin-google_oauth-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_recaptcha_site_key']?></label>
						<input class="form__input" name="recaptcha_site_key" value="<?=$product['recaptcha_site_key']?>" placeholder="<?=$lng['admin_recaptcha_site_key']?>" />
						<span id="admin-recaptcha_site_key-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_recaptcha_secret']?></label>
						<input class="form__input" name="recaptcha_secret" value="<?=$product['recaptcha_secret']?>" placeholder="<?=$lng['admin_recaptcha_secret']?>" />
						<span id="admin-recaptcha_secret-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"></label>
						<label class="form__checkbox" style="text-align: start;"><input type="checkbox" name="captcha_enabled" value="1"<?php if($product['captcha_enabled']=='1'){echo' checked="checked"';}?>><span></span><?=$lng['admin_captcha_enabled']?></label>
						<span id="admin-captcha_enabled-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label">Bitcointalk</label>
						<input class="form__input" name="bitcointalk" value="<?=$product['bitcointalk']?>" placeholder="<?=$lng['admin_placeholder_url']?>" />
						<span id="admin-bitcointalk-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label">Facebook</label>
						<input class="form__input" name="facebook" value="<?=$product['facebook']?>" placeholder="<?=$lng['admin_placeholder_url']?>" />
						<span id="admin-facebook-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label">Twitter</label>
						<input class="form__input" name="twitter" value="<?=$product['twitter']?>" placeholder="<?=$lng['admin_placeholder_url']?>" />
						<span id="admin-twitter-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label">Telegram</label>
						<input class="form__input" name="telegram" value="<?=$product['telegram']?>" placeholder="<?=$lng['admin_placeholder_url']?>" />
						<span id="admin-telegram-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label">VK</label>
						<input class="form__input" name="vk" value="<?=$product['vk']?>" placeholder="<?=$lng['admin_placeholder_url']?>" />
						<span id="admin-vk-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label">Youtube</label>
						<input class="form__input" name="youtube" value="<?=$product['youtube']?>" placeholder="<?=$lng['admin_placeholder_url']?>" />
						<span id="admin-youtube-error" class="form__error" style="display: none;"></span>
					</div>
				<div class="text--center">
					<div class="m-spinner m-spinner--auth m-spinner--lg" id="admin_main-loading" style="display: none;"></div>
					<button class="btn" type="button" id="admin_main-button"><?=$lng['button_save']?></button>
				</div>
				</form>
				</div>
				<div class="tab-pane js-calc-tab fade in" id="settings">
				<form class="form" id="admin_settings-form" method="post" action="" novalidate="novalidate">
				<input type="hidden" name="do" value="admin_settings">
					<div class="form__group"></div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_token_price']?></label>
						<input class="form__input" name="token_price" value="<?=$product['token_price']?>" placeholder="<?=$lng['admin_token_price']?>" />
						<span id="admin-token_price-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_token_price_eth']?></label>
						<input class="form__input" name="token_price_eth" value="<?=$product['token_price_eth']?>" placeholder="<?=$lng['admin_placeholder_token_price_eth']?>" />
						<span id="admin-token_price_eth-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_bonus_percent']?></label>
						<input class="form__input" name="bonus_percent" value="<?=$product['bonus_percent']?>" placeholder="<?=$lng['admin_bonus_percent']?>" />
						<span id="admin-bonus_percent-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_referral_percent']?></label>
						<input class="form__input" name="referral_percent" value="<?=$product['referral_percent']?>" placeholder="<?=$lng['admin_referral_percent']?>" />
						<span id="admin-referral_percent-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_signup_tokens']?></label>
						<input class="form__input" name="signup_tokens" value="<?=$product['signup_tokens']?>" placeholder="<?=$lng['admin_signup_tokens']?>" />
						<span id="admin-signup_tokens-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_support_email']?></label>
						<input class="form__input" name="support_email" value="<?=$product['support_email']?>" placeholder="<?=$lng['admin_placeholder_email']?>" />
						<span id="admin-support_email-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"></label>
						<label class="form__checkbox" style="text-align: start;"><input type="checkbox" name="email_activation" value="1"<?php if($product['email_activation']=='1'){echo' checked="checked"';}?>><span></span><?=$lng['admin_email_activation']?></label>
						<span id="admin-email_activation-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"></label>
						<label class="form__checkbox" style="text-align: start;"><input type="checkbox" name="ethereum_field_required" value="1"<?php if($product['ethereum_field_required']=='1'){echo' checked="checked"';}?>><span></span><?=$lng['admin_ethereum_field_required']?></label>
						<span id="admin-ethereum_field_required-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_currency']?></label>
						<?php foreach($currency_settings as $letters=>$enabled){?>
						<label class="form__checkbox" style="text-align: start;"><input type="checkbox" name="currency_<?=$letters?>" value="1"<?php if($enabled=='1'){echo' checked="checked"';}?>><span></span><?=$currency_names[$letters]?> (<?=$letters?>)</label>
						<?php }?>
						<span id="admin-currency-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_SBD_wallet']?></label>
						<input class="form__input" name="SBD_wallet" value="<?=$product['SBD_wallet']?>" placeholder="<?=$lng['admin_SBD_wallet']?>" />
						<span id="admin-SBD_wallet-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_GOLOS_wallet']?></label>
						<input class="form__input" name="GOLOS_wallet" value="<?=$product['GOLOS_wallet']?>" placeholder="<?=$lng['admin_GOLOS_wallet']?>" />
						<span id="admin-GOLOS_wallet-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_BTS_wallet']?></label>
						<input class="form__input" name="BTS_wallet" value="<?=$product['BTS_wallet']?>" placeholder="<?=$lng['admin_BTS_wallet']?>" />
						<span id="admin-BTS_wallet-error" class="form__error" style="display: none;"></span>
					</div>
				<div class="text--center">
					<div class="m-spinner m-spinner--auth m-spinner--lg" id="admin_settings-loading" style="display: none;"></div>
					<button class="btn" type="button" id="admin_settings-button"><?=$lng['button_save']?></button>
				</div>
				</form>
				</div>
				<div class="tab-pane js-calc-tab fade in" id="payments">
					<div class="form__group"></div>
					На данный момент этот раздел не готов и находится разработке.
				</div>
				<div class="tab-pane js-calc-tab fade in" id="users">
					<div class="form__group"></div>
<?php
$query = $db->prepare("SELECT id, time, email, country_code, ethereum_balance, balance, balance_bonus, balance_referrer, SBD_address_provided, GOLOS_address_provided, BTS_address_provided FROM users WHERE isactive = ? AND admin = ? AND ethereum_address_provided = ? AND (balance > ? OR balance_bonus > ? OR balance_referrer > ?) AND product_id = ? ORDER BY id DESC");
$query->execute(array('1','0','1','1','1','1',$product['id']));
if($query->rowCount()==0){echo'<div class="well"><div class="well__title">'.$lng['admin_tab_users'].'</div><div class="text--center"><span class="well__btn well__btn--disabled">'.$lng['userlist_no_users'].'</span></div></div>';}else{?>
		<div class="well">
			<div class="well__title"><?=$lng['admin_tab_users']?></div>
			<div class="table">
				<table>
					<thead>
						<tr>
							<th><?=$lng['userlist_id']?></th>
							<th><?=$lng['userlist_time']?></th>
							<th><?=$lng['userlist_email']?></th>
							<th><?=$lng['userlist_country_code']?></th>
							<th><?=$lng['tab_ethereum_address']?></th>
							<?php if($currency_settings['SBD']=='1'){echo'<th>SBD</th>';}?>
							<?php if($currency_settings['GOLOS']=='1'){echo'<th>GOLOS</th>';}?>
							<?php if($currency_settings['BTS']=='1'){echo'<th>BTS</th>';}?>
							<th><?=$lng['userlist_ethereum_balance']?></th>
							<th><?=$lng['userlist_overall_balance']?></th>
							<th>Twitter</th>
							<th>Facebook ID</th>
						</tr>
					</thead>
					<tbody>
<?php
while($row=$query->fetch(\PDO::FETCH_ASSOC)){

#Кошельки пользователя
if($row['ethereum_address_provided']=='0'){$row['ethereum_address']='';}
if($row['SBD_address_provided']=='0'){$row['SBD_address']='';}
if($row['GOLOS_address_provided']=='0'){$row['GOLOS_address']='';}
if($row['BTS_address_provided']=='0'){$row['BTS_address']='';}

#Ссылки на социальные сети
if($row['twitter_username']!=''){$twitter='<a href="https://twitter.com/'.$row['twitter_username'].'" target="_blank" rel="nofollow">'.$row['twitter_username'].'</a>';}
if($row['oauth_facebook']!=''){$facebook='<a href="https://facebook.com/'.$row['oauth_facebook'].'" target="_blank" rel="nofollow">'.$row['oauth_facebook'].'</a>';}

$wallets_add='';

if($currency_settings['ETH']=='1'){
if($row['ethereum_address_provided']=='1'){
$query = $db->prepare("SELECT wallet_address FROM user_wallets WHERE uid = ? AND currency = ? AND product_id = ? ORDER BY id DESC");
$query->execute(array($row['id'],'ETH',$product['id']));
$wallets_add.='<td>'.$query->fetch(\PDO::FETCH_ASSOC)['wallet_address'].'</td>';}else{$wallets_add.='<td></td>';}
}

if($currency_settings['SBD']=='1'){
if($row['SBD_address_provided']=='1'){
$query = $db->prepare("SELECT wallet_address FROM user_wallets WHERE uid = ? AND currency = ? AND product_id = ? ORDER BY id DESC");
$query->execute(array($row['id'],'SBD',$product['id']));
$wallets_add.='<td>'.$query->fetch(\PDO::FETCH_ASSOC)['wallet_address'].'</td>';}else{$wallets_add.='<td></td>';}
}

if($currency_settings['GOLOS']=='1'){
if($row['GOLOS_address_provided']=='1'){
$query = $db->prepare("SELECT wallet_address FROM user_wallets WHERE uid = ? AND currency = ? AND product_id = ? ORDER BY id DESC");
$query->execute(array($row['id'],'GOLOS',$product['id']));
$wallets_add.='<td>'.$query->fetch(\PDO::FETCH_ASSOC)['wallet_address'].'</td>';}else{$wallets_add.='<td></td>';}
}

if($currency_settings['BTS']=='1'){
if($row['BTS_address_provided']=='1'){
$query = $db->prepare("SELECT wallet_address FROM user_wallets WHERE uid = ? AND currency = ? AND product_id = ? ORDER BY id DESC");
$query->execute(array($row['id'],'BTS',$product['id']));
$wallets_add.='<td>'.$query->fetch(\PDO::FETCH_ASSOC)['wallet_address'].'</td>';}else{$wallets_add.='<td></td>';}
}

echo'<tr><td>'.$row['id'].'</td><td>'.date('d/m/Y H:i',$row['time']).'</td><td>'.$row['email'].'</td><td>'.$row['country_code'].'</td>'.$wallets_add.'<td><span class="portal-value portal-value--sm">'.$row['ethereum_balance'].'</span></td><td><span class="portal-value portal-value--sm">'.round(($row['balance']+$row['balance_bonus']+$row['balance_referrer']),4).'</span></td><td>'.$twitter.'</td><td>'.$facebook.'</td></tr>';}
?>				
					</tbody>
				</table>
			</div>
		</div>
<?php }?>

<?php
$query = $db->prepare("SELECT id, time, email, country_code, ethereum_balance, balance, balance_bonus, balance_referrer, SBD_address_provided, GOLOS_address_provided, BTS_address_provided FROM users WHERE isactive = ? AND admin = ? AND product_id = ? ORDER BY id DESC");
$query->execute(array('1','0',$product['id']));
if($query->rowCount()==0){echo'<div class="well"><div class="well__title">'.$lng['admin_tab_users'].' (all users)</div><div class="text--center"><span class="well__btn well__btn--disabled">'.$lng['userlist_no_users'].'</span></div></div>';}else{?>
		<div class="well">
			<div class="well__title"><?=$lng['admin_tab_users']?> (all users)</div>
			<div class="table">
				<table>
					<thead>
						<tr>
							<th><?=$lng['userlist_id']?></th>
							<th><?=$lng['userlist_time']?></th>
							<th><?=$lng['userlist_email']?></th>
							<th><?=$lng['userlist_country_code']?></th>
							<th><?=$lng['tab_ethereum_address']?></th>
							<?php if($currency_settings['SBD']=='1'){echo'<th>SBD</th>';}?>
							<?php if($currency_settings['GOLOS']=='1'){echo'<th>GOLOS</th>';}?>
							<?php if($currency_settings['BTS']=='1'){echo'<th>BTS</th>';}?>
							<th><?=$lng['userlist_ethereum_balance']?></th>
							<th><?=$lng['userlist_overall_balance']?></th>
							<th>Twitter</th>
							<th>Facebook ID</th>
						</tr>
					</thead>
					<tbody>
<?php
while($row=$query->fetch(\PDO::FETCH_ASSOC)){

#Кошельки пользователя
if($row['ethereum_address_provided']=='0'){$row['ethereum_address']='';}
if($row['SBD_address_provided']=='0'){$row['SBD_address']='';}
if($row['GOLOS_address_provided']=='0'){$row['GOLOS_address']='';}
if($row['BTS_address_provided']=='0'){$row['BTS_address']='';}

#Ссылки на социальные сети
if($row['twitter_username']!=''){$twitter='<a href="https://twitter.com/'.$row['twitter_username'].'" target="_blank" rel="nofollow">'.$row['twitter_username'].'</a>';}
if($row['oauth_facebook']!=''){$facebook='<a href="https://facebook.com/'.$row['oauth_facebook'].'" target="_blank" rel="nofollow">'.$row['oauth_facebook'].'</a>';}

$wallets_add='';

if($currency_settings['ETH']=='1'){
if($row['ethereum_address_provided']=='1'){
$query = $db->prepare("SELECT wallet_address FROM user_wallets WHERE uid = ? AND currency = ? AND product_id = ? ORDER BY id DESC");
$query->execute(array($row['id'],'ETH',$product['id']));
$wallets_add.='<td>'.$query->fetch(\PDO::FETCH_ASSOC)['wallet_address'].'</td>';}else{$wallets_add.='<td></td>';}
}

if($currency_settings['SBD']=='1'){
if($row['SBD_address_provided']=='1'){
$query = $db->prepare("SELECT wallet_address FROM user_wallets WHERE uid = ? AND currency = ? AND product_id = ? ORDER BY id DESC");
$query->execute(array($row['id'],'SBD',$product['id']));
$wallets_add.='<td>'.$query->fetch(\PDO::FETCH_ASSOC)['wallet_address'].'</td>';}else{$wallets_add.='<td></td>';}
}

if($currency_settings['GOLOS']=='1'){
if($row['GOLOS_address_provided']=='1'){
$query = $db->prepare("SELECT wallet_address FROM user_wallets WHERE uid = ? AND currency = ? AND product_id = ? ORDER BY id DESC");
$query->execute(array($row['id'],'GOLOS',$product['id']));
$wallets_add.='<td>'.$query->fetch(\PDO::FETCH_ASSOC)['wallet_address'].'</td>';}else{$wallets_add.='<td></td>';}
}

if($currency_settings['BTS']=='1'){
if($row['BTS_address_provided']=='1'){
$query = $db->prepare("SELECT wallet_address FROM user_wallets WHERE uid = ? AND currency = ? AND product_id = ? ORDER BY id DESC");
$query->execute(array($row['id'],'BTS',$product['id']));
$wallets_add.='<td>'.$query->fetch(\PDO::FETCH_ASSOC)['wallet_address'].'</td>';}else{$wallets_add.='<td></td>';}
}

echo'<tr><td>'.$row['id'].'</td><td>'.date('d/m/Y H:i',$row['time']).'</td><td>'.$row['email'].'</td><td>'.$row['country_code'].'</td>'.$wallets_add.'<td><span class="portal-value portal-value--sm">'.$row['ethereum_balance'].'</span></td><td><span class="portal-value portal-value--sm">'.round(($row['balance']+$row['balance_bonus']+$row['balance_referrer']),4).'</span></td><td>'.$twitter.'</td><td>'.$facebook.'</td></tr>';}
?>				
					</tbody>
				</table>
			</div>
		</div>
<?php }?>

<?php
$query = $db->prepare("SELECT id, time, email, country_code, ethereum_balance, balance, balance_bonus, balance_referrer, SBD_address_provided, GOLOS_address_provided, BTS_address_provided FROM users WHERE isactive = ? AND admin = ? AND product_id = ? ORDER BY id DESC");
$query->execute(array('0','0',$product['id']));
if($query->rowCount()==0){echo'<div class="well"><div class="well__title">'.$lng['admin_tab_users'].' (not activated)</div><div class="text--center"><span class="well__btn well__btn--disabled">'.$lng['userlist_no_users'].'</span></div></div>';}else{?>
		<div class="well">
			<div class="well__title"><?=$lng['admin_tab_users']?> (not activated)</div>
			<div class="table">
				<table>
					<thead>
						<tr>
							<th><?=$lng['userlist_id']?></th>
							<th><?=$lng['userlist_time']?></th>
							<th><?=$lng['userlist_email']?></th>
							<th><?=$lng['userlist_country_code']?></th>
							<th><?=$lng['tab_ethereum_address']?></th>
							<?php if($currency_settings['SBD']=='1'){echo'<th>SBD</th>';}?>
							<?php if($currency_settings['GOLOS']=='1'){echo'<th>GOLOS</th>';}?>
							<?php if($currency_settings['BTS']=='1'){echo'<th>BTS</th>';}?>
							<th><?=$lng['userlist_ethereum_balance']?></th>
							<th><?=$lng['userlist_overall_balance']?></th>
							<th>Twitter</th>
							<th>Facebook ID</th>
						</tr>
					</thead>
					<tbody>
<?php
while($row=$query->fetch(\PDO::FETCH_ASSOC)){

#Кошельки пользователя
if($row['ethereum_address_provided']=='0'){$row['ethereum_address']='';}
if($row['SBD_address_provided']=='0'){$row['SBD_address']='';}
if($row['GOLOS_address_provided']=='0'){$row['GOLOS_address']='';}
if($row['BTS_address_provided']=='0'){$row['BTS_address']='';}

#Ссылки на социальные сети
if($row['twitter_username']!=''){$twitter='<a href="https://twitter.com/'.$row['twitter_username'].'" target="_blank" rel="nofollow">'.$row['twitter_username'].'</a>';}
if($row['oauth_facebook']!=''){$facebook='<a href="https://facebook.com/'.$row['oauth_facebook'].'" target="_blank" rel="nofollow">'.$row['oauth_facebook'].'</a>';}

$wallets_add='';

if($currency_settings['ETH']=='1'){
if($row['ethereum_address_provided']=='1'){
$query = $db->prepare("SELECT wallet_address FROM user_wallets WHERE uid = ? AND currency = ? AND product_id = ? ORDER BY id DESC");
$query->execute(array($row['id'],'ETH',$product['id']));
$wallets_add.='<td>'.$query->fetch(\PDO::FETCH_ASSOC)['wallet_address'].'</td>';}else{$wallets_add.='<td></td>';}
}

if($currency_settings['SBD']=='1'){
if($row['SBD_address_provided']=='1'){
$query = $db->prepare("SELECT wallet_address FROM user_wallets WHERE uid = ? AND currency = ? AND product_id = ? ORDER BY id DESC");
$query->execute(array($row['id'],'SBD',$product['id']));
$wallets_add.='<td>'.$query->fetch(\PDO::FETCH_ASSOC)['wallet_address'].'</td>';}else{$wallets_add.='<td></td>';}
}

if($currency_settings['GOLOS']=='1'){
if($row['GOLOS_address_provided']=='1'){
$query = $db->prepare("SELECT wallet_address FROM user_wallets WHERE uid = ? AND currency = ? AND product_id = ? ORDER BY id DESC");
$query->execute(array($row['id'],'GOLOS',$product['id']));
$wallets_add.='<td>'.$query->fetch(\PDO::FETCH_ASSOC)['wallet_address'].'</td>';}else{$wallets_add.='<td></td>';}
}

if($currency_settings['BTS']=='1'){
if($row['BTS_address_provided']=='1'){
$query = $db->prepare("SELECT wallet_address FROM user_wallets WHERE uid = ? AND currency = ? AND product_id = ? ORDER BY id DESC");
$query->execute(array($row['id'],'BTS',$product['id']));
$wallets_add.='<td>'.$query->fetch(\PDO::FETCH_ASSOC)['wallet_address'].'</td>';}else{$wallets_add.='<td></td>';}
}

echo'<tr><td>'.$row['id'].'</td><td>'.date('d/m/Y H:i',$row['time']).'</td><td>'.$row['email'].'</td><td>'.$row['country_code'].'</td>'.$wallets_add.'<td><span class="portal-value portal-value--sm">'.$row['ethereum_balance'].'</span></td><td><span class="portal-value portal-value--sm">'.round(($row['balance']+$row['balance_bonus']+$row['balance_referrer']),4).'</span></td><td>'.$twitter.'</td><td>'.$facebook.'</td></tr>';}
?>				
					</tbody>
				</table>
			</div>
		</div>
<?php }?>
				</div>
				<div class="tab-pane js-calc-tab fade in" id="tokens">
				<form class="form" id="admin_search-form" method="post" action="" novalidate="novalidate">
				<input type="hidden" name="do" value="admin_search">
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_search_title']?></label>
						<input class="form__input" id="user_email" name="user_email" value="" placeholder="<?=$lng['admin_placeholder_email']?>" />
						<span id="admin-user_email-error" class="form__error" style="display: none;"></span>
					</div>
				<div class="text--center">
					<div class="m-spinner m-spinner--auth m-spinner--lg" id="admin_search-loading" style="display: none;"></div>
					<button class="btn" type="button" id="admin_search-button"><?=$lng['button_search']?></button>
				</div>
				</form>
				
				<form class="form" id="admin_tokens-form" method="post" action="" novalidate="novalidate">
				<input type="hidden" name="do" value="admin_tokens">
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_tokens_user_id']?></label>
						<input class="form__input" id="user_id" name="user_id" value="" placeholder="<?=$lng['admin_tokens_user_id_placeholder']?>" />
						<span id="admin-user_id-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_tokens_currency']?></label>
						<div class="select"><select class="form__input" id="currency_" name="currency_"><option value=""><?=$lng['admin_tokens_currency_placeholder']?></option><?php foreach($currency_settings as $letters=>$enabled){if($enabled=='1'){echo'<option value="'.$letters.'">'.$currency_names[$letters].' ('.$letters.')</option>';}}?><option value="USD">PayPal (USD)</option></select></div>
						<span id="admin-currency_-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_tokens_amount']?></label>
						<input class="form__input" id="amount" name="amount" value="" placeholder="<?=$lng['admin_tokens_amount_placeholder']?>" />
						<span id="admin-amount-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_tokens_tokens_amount']?></label>
						<input class="form__input" id="tokens_amount" name="tokens_amount" value="" placeholder="<?=$lng['admin_tokens_tokens_amount_placeholder']?>" />
						<span id="admin-tokens_amount-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_tokens_act']?></label>
						<div class="select"><select class="form__input" id="act" name="act"><option value="add"><?=$lng['admin_tokens_act_add']?></option><option value="remove"><?=$lng['admin_tokens_act_remove']?></option></select></div>
						<span id="admin-act-error" class="form__error" style="display: none;"></span>
					</div>
					<div class="form__group">
						<label class="form__label"><?=$lng['admin_tokens_comment']?></label>
						<input class="form__input" id="comment" name="comment" value="" placeholder="<?=$lng['admin_tokens_comment_placeholder']?>" />
						<span id="admin-comment-error" class="form__error" style="display: none;"></span>
					</div>
				<div class="text--center">
					<div class="m-spinner m-spinner--auth m-spinner--lg" id="admin_tokens-loading" style="display: none;"></div>
					<button class="btn" type="button" id="admin_tokens-button"><?=$lng['button_admin_tokens']?></button>
				</div>
				</form>
					<div class="form__group"></div>
<?php
$query = $db->prepare("SELECT * FROM balance_actions WHERE product_id = ? ORDER BY id DESC");
$query->execute(array($product['id']));
if($query->rowCount()==0){echo'<div class="well"><div class="well__title">'.$lng['admin_tokens_history'].'</div><div class="text--center"><span class="well__btn well__btn--disabled">'.$lng['no_transactions'].'</span></div></div>';}else{?>
		<div class="well">
			<div class="well__title"><?=$lng['admin_tokens_history']?></div>
			<div class="table">
				<table>
					<thead>
						<tr>
							<th><?=$lng['admin_tokens_user_id']?></th>
							<th><?=$lng['orders_time']?></th>
							<th><?=$lng['admin_tokens_admin_id']?></th>
							<th><?=$lng['admin_tokens_act']?></th>
							<th><?=$lng['orders_price']?></th>
							<th><?=$lng['admin_tokens_tokens_amount']?></th>
							<th><?=$lng['admin_tokens_comment']?></th>
						</tr>
					</thead>
					<tbody>
<?php
while($row=$query->fetch(\PDO::FETCH_ASSOC)){

if($row['type']=='add'){$type=$lng['admin_tokens_act_added'];}
if($row['type']=='remove'){$type=$lng['admin_tokens_act_removed'];}

echo'<tr><td>'.$row['uid'].'</td><td>'.date('d/m/Y H:i',$row['time']).'</td><td>'.$row['admin'].'</td><td>'.$type.'</td><td>'.$row['amount'].' '.$row['currency'].'</td><td><span class="portal-value portal-value--sm">'.$row['tokens_amount'].'</span></td><td>'.$row['comment'].'</td></tr>';}
?>				
					</tbody>
				</table>
			</div>
		</div>
<?php }?>
				</div>
				<div class="tab-pane js-calc-tab fade in" id="support">
					<div class="form__group"></div>
<?php
$query = $db->prepare("SELECT * FROM support WHERE product_id = ? ORDER BY id DESC");
$query->execute(array($product['id']));
if($query->rowCount()==0){echo'<div class="well"><div class="well__title">'.$lng['admin_tab_support'].'</div><div class="text--center"><span class="well__btn well__btn--disabled">'.$lng['no_support'].'</span></div></div>';}else{?>
		<div class="well">
			<div class="well__title"><?=$lng['admin_tab_support']?></div>
			<div class="table">
				<table>
					<thead>
						<tr>
							<th>Email</th>
							<th><?=$lng['admin_support_time']?></th>
							<th><?=$lng['support_subject']?></th>
							<th><?=$lng['support_message']?></th>
						</tr>
					</thead>
					<tbody>
<?php
while($row=$query->fetch(\PDO::FETCH_ASSOC)){

$query_email = $db->prepare("SELECT email FROM users WHERE id = ? AND product_id = ? ORDER BY id DESC");
$query_email->execute(array($row['uid'],$product['id']));

if($row['subject']=='1'){$subject=$lng['support_payments'];}else{$subject=$lng['support_tech'];}

echo'<tr><td>'.($query_email->fetch(\PDO::FETCH_ASSOC)['email']).'</td><td>'.date('d/m/Y H:i',$row['time']).'</td><td>'.$subject.'</td><td>'.$row['message'].'</td></tr>';}
?>				
					</tbody>
				</table>
			</div>
		</div>
<?php }?>
				</div>
				<div class="tab-pane js-calc-tab fade in" id="stats">
					<div class="form__group"></div>
		<div class="well">
			<div class="well__title"><?=$lng['admin_tab_stats']?></div>
			<div class="table">
				<table>
					<tbody>
					<tr><td><?=$lng['admin_stats_raised']?> (<?=$lng['trough_portal']?>)</td><td><b><?=$product['usd_raised']?>$</b></td></tr>
					<tr><td><?=$lng['admin_stats_raised']?> (<?=$lng['trough_portal']?>)</td><td><b><?=$product['eth_raised']?> ETH</b></td></tr>
					<tr><td><?=$lng['admin_stats_raised']?> (<?=$lng['smartcontract']?>)</td><td><b><?=$product['eth_raised_smartcontract']?> ETH</b></td></tr>
					<tr><td colspan="2"><b><?=$lng['admin_stats_tokens']?></b></td></tr>
					<tr><td><?=$lng['admin_stats_tokens_sale']?></td><td><b><?=$product['tokens_sold']?></b></td></tr>
					<tr><td><?=$lng['admin_stats_tokens_bonus']?></td><td><b><?=$product['tokens_bonus']?></b></td></tr>
					<tr><td><?=$lng['admin_stats_tokens_referral']?></td><td><b><?=$product['tokens_referrer']?></b></td></tr>
					<tr><td><?=$lng['admin_stats_tokens_signup']?></td><td><b><?=$product['tokens_signup']?></b></td></tr>
					</tbody>
				</table>
			</div>
		</div>
				</div>
			</div>
		</div>
	</div>
<?php require 'modules/footer.php';exit;?>
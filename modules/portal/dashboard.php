<?php
if(!isset($settings) || !$logged_in['flag'] || $module!='dashboard'){header('location: /');exit;}

#BEGIN etherscan api
if(time()>($user['ethereum_balance_timestamp']+600) && $user['ethereum_address_provided']=='1'){
$data_balance=file_get_contents('https://api.etherscan.io/api?module=account&action=tokenbalance&tag=latest&contractaddress='.$product['smartcontract_address'].'&address='.$user['ethereum_address'].'&apikey='.$product['etherscan_api_key'].'');
if($data_balance!==false){
$array_balance=json_decode($data_balance, true);
if(is_numeric($array_balance['result']) && $user['ethereum_balance']!=$array_balance['result']){
$query = $db->prepare("UPDATE users SET ethereum_balance = ?, ethereum_balance_timestamp = ? WHERE id = ?");
$query->execute(array_values(array($array_balance['result'],time(),$user['id'])));$user['ethereum_balance']=$array_balance['result'];
}}}
#END etherscan api
?>
	<div class="wrapper">
		<div class="container">
			<form class="form form--portal-big">
				<div class="form__content">
					<div class="form__title"> <span><?=$lng['menu_main']?></span></div>
				  <h2 align="center" style="font-size: 19px; text-align: center;">WELCOME TO THE LUCYD WALLET</h2>
<div class="guide" style="margin: 0 0 15px 0; padding: 10px; background-image: url('/assets/images/backgrounds/lcd_info_bg.jpg');">
   <p style="padding-bottom: 10px; text-align: center;"><u style="font-weight: 900;">All tokens will be delivered after the ICO</u>.</p>
   <p style="padding-bottom: 10px;">All major currencies accepted. LCD will become transferable to other wallets and exchanges after ICO end, and bonus tokens will issued at this time as well. If you contribute enough for a Lens preorder, you must retain 5,000 LCD to exchange for it when available. LCD bonus rungs are as follows (equivalent amounts in crypto get same bonus):</p>
   <ul style="text-align: left; padding: 15px 15px 15px 35px; color: black;">
      <li >$600 and up: 10% extra LCD.</li>
      <li >$1200 and up: 10% extra LCD and automatic, fully paid preorder of a beta Lucyd Lens.</li>
      <li >$3000 and up: 15% extra LCD and Lens preorder.</li>
      <li >$6000 and up: 20% extra LCD and Lens preorder.</li>
      <li >$9000 and up: 25% extra LCD and Lens preorder.</li>
      <li >$18000 and up: 35% extra LCD and Lens preorder.</li>
   </ul>
   <p style="text-align: left; padding: 0 15px 0 15px; color: black;">Top 50 donors will be named Lucyd Legends, receive a limited platinum-colored Lucyd Lens, a 3-year extended warranty, and 40% extra LCD.</p>
   <p style="text-align: left; padding: 0 15px 0 15px; color: black;">&nbsp;</p>
   <p align="center" style="text-align: left; padding: 0 15px 0 15px; color: black;"><strong>Refunds are only possible within 48 hours of transaction</strong></p>
</div>
						
					<!--<div class="form__black form__full black-data">
						<div class="black-data__text"><?=$lng['token_balance']?></div>
						<div class="black-data__value"><?=round(($user['balance']+$user['balance_bonus']+$user['balance_referrer']),4)?></div>
						<img class="black-data__logo" src="/assets/products/<?=$product['id']?>/logo_white.png" srcset="/assets/products/<?=$product['id']?>/logo_white@2x.png 2x" alt="" />
					</div>-->
					<div class="clearfix">
					
<style>
.progress{display:flex;overflow:hidden;font-size:.75rem;line-height:1rem;text-align:center;background-color:#e9ecef;border-radius:.25rem}
.progress-bar{height:1rem;line-height:1rem;color:#fff;background-color:#5867dd;transition:width .6s ease}
.progress .progress-bar{transition:all .5s ease;padding-bottom:.40rem}
.progress.m-progress--sm .progress-bar{height:6px;border-radius:3px}
.progress.m-progress--lg .progress-bar{height:20px;border-radius:4px}
.progress-sm{width:100%}
.percent {color: #1c1c1c;font-size: 20px;font-weight: 700;letter-spacing: 0.5px;position: relative;}
</style>

<?php
$total_supply=50000000;//50 mln
$target1=100000;
$target2=250000;
$target3=500000;
$target4=1500000;
$target5=10000000;
$sm_sold=$product['eth_raised_smartcontract']/0.00078125;
$total_sold=($product['tokens_sold']+$sm_sold);

$paypal=43209;

$usd_raised1=$paypal+$total_sold*$product['token_price'];

$percent1=round($usd_raised1/$target1*100);
$percent2=round($usd_raised1/$target2*100);
$percent3=round($usd_raised1/$target3*100);
$percent4=round($usd_raised1/$target4*100);
$percent5=round($usd_raised1/$target5*100);

$lng['progress_bar_title1']='Initial Goal ($100.000)';
$lng['progress_bar_title2']='Strategic Sessions Goal ($250.000)';
$lng['progress_bar_title3']='Organizational Goal ($500.000)';
$lng['progress_bar_title4']='Softcap ($1.500.000)';
$lng['progress_bar_title5']='Hardcap ($10.000.000)';
?>
<div class="form__row form__full">

<!--
<div class="row" style="padding-bottom: 8px;"><div class="col-sm-8"><div class="form__text"><?=$lng['progress_bar_title1']?></div></div><div class="col-sm-4 form__right"><div class="percent"><?=$percent1?>%</div>
</div></div>
<div class="row" style="padding-bottom: 15px;"><div class="col-sm-12"><div class="progress progress-sm m-progress--sm"><div class="progress-bar" style="width:<?=$percent1?>%;"></div></div></div></div>

<div class="row" style="padding-bottom: 8px;"><div class="col-sm-8"><div class="form__text"><?=$lng['progress_bar_title2']?></div></div><div class="col-sm-4 form__right"><div class="percent"><?=$percent2?>%</div>
</div></div>
<div class="row" style="padding-bottom: 15px;"><div class="col-sm-12"><div class="progress progress-sm m-progress--sm"><div class="progress-bar" style="width:<?=$percent2?>%;"></div></div></div></div>


<div class="row" style="padding-bottom: 8px;"><div class="col-sm-8"><div class="form__text"><?=$lng['progress_bar_title3']?></div></div><div class="col-sm-4 form__right"><div class="percent"><?=$percent3?>%</div>
</div></div>
<div class="row" style="padding-bottom: 15px;"><div class="col-sm-12"><div class="progress progress-sm m-progress--sm"><div class="progress-bar" style="width:<?=$percent3?>%;"></div></div></div></div>


<div class="row" style="padding-bottom: 8px;"><div class="col-sm-8"><div class="form__text"><?=$lng['progress_bar_title4']?></div></div><div class="col-sm-4 form__right"><div class="percent"><?=$percent4?>%</div>
</div></div>
<div class="row" style="padding-bottom: 15px;"><div class="col-sm-12"><div class="progress progress-sm m-progress--sm"><div class="progress-bar" style="width:<?=$percent4?>%;"></div></div></div></div>


<div class="row" style="padding-bottom: 8px;"><div class="col-sm-8"><div class="form__text"><?=$lng['progress_bar_title5']?></div></div><div class="col-sm-4 form__right"><div class="percent"><?=$percent5?>%</div>
</div></div>
<div class="row" style="padding-bottom: 15px;"><div class="col-sm-12"><div class="progress progress-sm m-progress--sm"><div class="progress-bar" style="width:<?=$percent5?>%;"></div></div></div></div>


<div class="row"><div class="col-sm-6" style="text-align: center;"><div class="portal-value portal-value--other portal-value--eth" style="font-size: 17px;"><?=number_format($product['eth_raised_smartcontract'], 2, '.', '.')?> ETH</div></div><div class="col-sm-6" style="text-align: center;"><div class="portal-value portal-value--other portal-value--usd" style="font-size: 17px;">$<?=number_format($product['usd_raised']+$paypal, 0, '.', ',')?></div></div></div>

-->

</div>
					
					
						<div class="form__row form__full form__row--grey">
							<div class="row">
								<div class="col-sm-8">
									<div class="form__text form__text--sm form__text--grey"><?=$lng['affiliate_link']?></div>
									<div class="form__text"> <a class="link form__link" href="<?=$product['url']?>?ref=<?=$user['id']?>" target="_blank"><?=$product['url']?>?ref=<?=$user['id']?></a>
									</div>
								</div>
								<div class="col-sm-4 form__right">
									<a class="js-copy btn btn--sm btn--transparent btn--copy" href="#" data-clipboard-text="<?=$product['url']?>?ref=<?=$user['id']?>"> <span><?=$lng['button_copy']?></span>
									</a>
								</div>
							</div>
						</div>
						<div class="form__row form__full">
							<div class="row">
								<div class="col-sm-8">
									<div class="form__text"><?=$lng['invested']?></div>
								</div>
								<div class="col-sm-4 form__right">
									<div class="portal-value"><?=round($user['balance'],4)?><?php if($user['ethereum_balance']>0){echo' (+ '.$user['ethereum_balance'].' '.$lng['in_ethereum'].')';}?></div>
								</div>
							</div>
						</div>
						<div class="form__row form__full">
							<div class="row">
								<div class="col-sm-8">
									<div class="form__text"><?=$lng['bonus']?></div>
								</div>
								<div class="col-sm-4 form__right">
									<div class="portal-value"><?=$user['balance_bonus']?></div>
								</div>
							</div>
						</div>
						<!--<div class="form__row form__full">
							<div class="row">
								<div class="col-sm-5">
									<div class="form__text"><?=$lng['invested_total']?></div>
								</div>
								<div class="col-sm-7 form__right">
									<div class="portal-value portal-value--other portal-value--usd">$<?=number_format($product['eth_raised_smartcontract']*$currency_to_usd['ETH'], 0, '.', ',')?></div>
									<div class="portal-value portal-value--other portal-value--eth"><?=number_format($product['eth_raised_smartcontract'], 2, '.', '.')?> ETH</div>
								</div>
							</div>
						</div>-->
					</div>
					<div class="text--center"><a class="btn btn--buy text--center" href="/buy/"><?=$lng['button_buy']?></a></div>
					<p style="text-align: center;padding-top:15px;">To easily acquire cryptocurrency, make an account at <a href="https://coinbase.com">coinbase.com</a>. For fiat or large contributions, please email <a href="mailto:finance@lucyd.co?Subject=Large%20Contribution%20" target="_top">finance@lucyd.co</a></p>
					<p style="text-align: center;padding-top:15px;">All purchases are made subject to the Terms and Conditions.</p>
					<p style="text-align: center;padding-top:15px;"><a href="/assets/docs/TermsAndConditions2017-2018.pdf" target="_blank">Terms & Conditions</a></p>
				</div>
			  <div id="vid" align="center">
		      <h2 class="portal-value">&nbsp;</h2></div>
			</form>
		</div>
<?php
$query = $db->prepare("SELECT id, transaction_id, time, status, system, currency, tokens_amount, tokens_amount_bonus, amount FROM payments WHERE uid = ? OR wallet_address = ? ORDER BY id DESC");
$query->execute(array($user['id'],$user['ethereum_address']));
if($query->rowCount()==0){echo'<div class="well"><div class="well__title">'.$lng['orders_title'].'</div><div class="text--center"><span class="well__btn well__btn--disabled">'.$lng['no_transactions'].'</span></div></div>';}else{?>
		<div class="well">
			<div class="well__title"><?=$lng['orders_title']?></div>
			<div class="table">
				<table>
					<thead>
						<tr>
							<th><?=$lng['orders_time']?></th>
							<th><?=$lng['orders_id']?></th>
							<th><?=$lng['orders_status']?></th>
							<th><?=$lng['orders_system']?></th>
							<th><?=$product['token_name']?></th>
							<th><?=$lng['orders_price']?></th>
						</tr>
					</thead>
					<tbody>
<?php
while($row=$query->fetch(\PDO::FETCH_ASSOC)){

if($row['status']>=100 || $row['status']==2){
$tx_color='green';$tx_status=$lng['transaction_completed'];
}else if($row['status']<0){
$tx_color='red';$tx_status=$lng['transaction_cancelled'];
}else{
$tx_color='yellow';$tx_status=$lng['transaction_pending'];
}

if($row['system']=='smartcontract'){$tokens_amount='<td><a class="link" href="https://etherscan.io/tx/'.$row['transaction_id'].'" target="_blank" rel="nofollow">'.$lng['view_on_etherscan'].'</a></td>';}else{$tokens_amount='<td><span class="portal-value portal-value--sm">'.$row['tokens_amount'].'</span></td>';}
echo'<tr><td>'.date('d/m/Y H:i',$row['time']).'</td><td><a class="link txinfo" href="#transaction" data-time="'.date('d/m/Y H:i',$row['time']).'" data-system="'.$lng['payment_'.$row['system'].''].'" data-id="#'.$row['id'].'" data-status="'.$tx_status.'" data-color="'.$tx_color.'" data-bonus="'.$row['tokens_amount_bonus'].'" data-tokens="'.$row['tokens_amount'].'" data-amount="'.$row['amount'].' '.$row['currency'].'">#'.$row['id'].'</a></td><td><span class="'.$tx_color.'">'.$tx_status.'</span></td><td>'.$lng['payment_'.$row['system'].''].'</td>'.$tokens_amount.'<td>'.$row['amount'].' '.$row['currency'].'</td></tr>';}
?>				
					</tbody>
				</table>
			</div>
		</div>
<?php }?>
	</div>
		
	<div class="remodal" data-remodal-id="transaction">
		<div class="popup">
			<div class="popup__header">
				<div class="popup__title"><?=$lng['transaction_title']?></div>
				<div class="popup__text text--center" id="tx-time"></div>
			</div>
			<div class="popup__content">
				<div class="popup__row">
					<div class="row">
						<div class="col-md-6"><?=$lng['orders_system']?></div>
						<div class="col-md-6">
							<div class="popup__right" id="tx-system"></div>
						</div>
					</div>
				</div>
				<div class="popup__row">
					<div class="row">
						<div class="col-md-6"><?=$lng['orders_id']?></div>
						<div class="col-md-6">
							<div class="popup__right" id="tx-id"></div>
						</div>
					</div>
				</div>
				<div class="popup__row">
					<div class="row">
						<div class="col-md-6"><?=$lng['orders_status']?></div>
						<div class="col-md-6">
							<div class="popup__right"><div id="tx-status"></div></div>
						</div>
					</div>
				</div>
				<div class="popup__row popup__row--portal" id="tx-bonus-div">
					<div class="row">
						<div class="col-md-6"><?=$lng['bonus_tokens']?></div>
						<div class="col-md-6">
							<div class="popup__right">
								<div class="portal-value" id="tx-bonus"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="popup__row popup__row--portal">
					<div class="row">
						<div class="col-md-6"><?=$product['token_name']?></div>
						<div class="col-md-6">
							<div class="popup__right">
								<div class="portal-value" id="tx-tokens"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="popup__row popup__row--portal">
					<div class="row">
						<div class="col-md-6"><?=$lng['transaction_total']?></div>
						<div class="col-md-6">
							<div class="popup__right">
								<div class="portal-value portal-value--other portal-value--usd" style="font-size: 20px;" id="tx-amount"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="popup__footer text--center">
				<p class="text"><?=$lng['transaction_support']?></p><a class="btn btn--buy" href="/support/"><?=$lng['button_ticket']?></a>
			</div>
		</div>
	</div>
<?php require 'modules/footer.php';?>
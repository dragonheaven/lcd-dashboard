<?php
require '../../engine.php';

header("Access-Control-Allow-Origin: *");
header('Content-type:application/json;charset=utf-8');

$sm_sold=$product['eth_raised_smartcontract']*$currency_to_usd['ETH'];

$paypal=43209;

$usd_raised1=$paypal+$sm_sold+$product['usd_raised'];

echo json_encode(['usd_raised'=>''.$usd_raised1.'','ethereum_raised'=>''.$product['eth_raised'].'','ethereum_raised_smartcontract'=>''.$product['eth_raised_smartcontract'].'','tokens_sold'=>''.$product['tokens_sold'].'','tokens_bonus'=>''.$product['tokens_bonus'].'','tokens_referrer'=>''.$product['tokens_referrer'].'','tokens_signup'=>''.$product['tokens_signup'].'','token_price'=>''.$product['token_price'].'','token_price_eth'=>''.$product['token_price_eth'].'','bonus_percent'=>''.$product['bonus_percent'].'','referral_percent'=>''.$product['referral_percent'].'']);
?>
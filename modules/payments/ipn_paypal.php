<?php
namespace Listener;

file_put_contents('paypal_post.txt', print_r($_POST,true), FILE_APPEND);
file_put_contents('paypal_get.txt', print_r($_GET,true), FILE_APPEND);
file_put_contents('paypal_server.txt', print_r($_SERVER,true), FILE_APPEND);
file_put_contents('paypal_request.txt', print_r($_REQUEST,true), FILE_APPEND);

if(!count($_POST)){exit();}

set_time_limit(0);
require '../../engine.php';

// Set this to true to use the sandbox endpoint during testing:
$enable_sandbox = false;

// Use this to specify all of the email addresses that you have attached to paypal:
$my_email_addresses = array("kdabrowski@lucyd.co");

// Set this to true to send a confirmation email:
$send_confirmation_email = false;
$confirmation_email_address = "My Name <my_email_address@gmail.com>";
$from_email_address = "My Name <my_email_address@gmail.com>";

// Set this to true to save a log file:
$save_log_file = true;
$log_file_dir = __DIR__ . "/logs";

// Here is some information on how to configure sendmail:
// http://php.net/manual/en/function.mail.php#118210



require 'ipn_paypal.inc.php';
use PaypalIPN;
$ipn = new PaypalIPN();
if ($enable_sandbox) {
    $ipn->useSandbox();
}
$verified = $ipn->verifyIPN();

$data_text = "";
foreach ($_POST as $key => $value) {
    $data_text .= $key . " = " . $value . "\r\n";
}

$test_text = "";
if ($_POST["test_ipn"] == 1) {
    $test_text = "Test ";
}

// Check the receiver email to see if it matches your list of paypal email addresses
$receiver_email_found = false;
foreach ($my_email_addresses as $a) {
    if (strtolower($_POST["receiver_email"]) == strtolower($a)) {
        $receiver_email_found = true;
        break;
    }
}

date_default_timezone_set("America/Los_Angeles");
list($year, $month, $day, $hour, $minute, $second, $timezone) = explode(":", date("Y:m:d:H:i:s:T"));
$date = $year . "-" . $month . "-" . $day;
$timestamp = $date . " " . $hour . ":" . $minute . ":" . $second . " " . $timezone;
$dated_log_file_dir = $log_file_dir . "/" . $year . "/" . $month;

$paypal_ipn_status = "VERIFICATION FAILED";
if ($verified) {
    $paypal_ipn_status = "RECEIVER EMAIL MISMATCH";
    if ($receiver_email_found) {
        $paypal_ipn_status = "Completed Successfully";


        // Process IPN
        // A list of variables are available here:
        // https://developer.paypal.com/webapps/developer/docs/classic/ipn/integration-guide/IPNandPDTVariables/

		if($_POST["txn_type"]!='web_accept' || $_POST["item_name"]!='LCD Tokens'){exit();}
		
		$user_id='0';
		$tokens_amount='0';
		$tokens_amount_bonus='0';
		$status='0';
		if($_POST["mc_gross"]==150.00){$tokens_amount='600';}
		if($_POST["mc_gross"]==600.00){$tokens_amount='2400';$tokens_amount_bonus='240';}
		if($_POST["mc_gross"]==1200.00){$tokens_amount='4800';$tokens_amount_bonus='480';}
		if($_POST["mc_gross"]==3000.00){$tokens_amount='12000';$tokens_amount_bonus='1800';}
		if($_POST["mc_gross"]==6000.00){$tokens_amount='24000';$tokens_amount_bonus='4800';}
		if($_POST["mc_gross"]==9000.00){$tokens_amount='36000';$tokens_amount_bonus='9000';}
		
		if($_POST["payment_status"]=="Completed" || $_POST["payment_status"]=="Created" || $_POST["payment_status"]=="Canceled_Reversal" || $_POST["payment_status"]=="Processed"){$status='100';$action='+';}
		if($_POST["payment_status"]=="Pending" || $_POST["payment_status"]=="Created"){$status='1';$action='no';}
		if($_POST["payment_status"]=="Denied" || $_POST["payment_status"]=="Expired" || $_POST["payment_status"]=="Failed" || $_POST["payment_status"]=="Refunded" || $_POST["payment_status"]=="Reversed" || $_POST["payment_status"]=="Voided"){$status='-1';$action='no';}
		
		if($tokens_amount=='0' || $status=='0'){exit();}
		
		if(strpos($_POST["option_selection2"],'|')!==false){
			$arr=explode('|',$_POST["option_selection2"]);
			
			if(is_numeric($arr[0]) && $arr[0]>0){
				
$query = $db->prepare("SELECT id, referrer FROM users WHERE id = ? AND product_id = ?");
$query->execute(array($arr[0],$product['id']));
if($query->rowCount()>0){$row=$query->fetch(\PDO::FETCH_ASSOC);$user_id=$row['id'];$user_referrer=$row['referrer'];}
				
			}
			
			if($user_id=='0' && filter_var($arr[1],FILTER_VALIDATE_EMAIL)){
				
$query = $db->prepare("SELECT id, referrer FROM users WHERE email = ? AND product_id = ?");
$query->execute(array($arr[1],$product['id']));
if($query->rowCount()>0){$row=$query->fetch(\PDO::FETCH_ASSOC);$user_id=$row['id'];$user_referrer=$row['referrer'];}
				
			}
			
			if($user_id=='0'){
				
$query = $db->prepare("SELECT id, referrer FROM users WHERE email = ? AND product_id = ?");
$query->execute(array($_POST['payer_email'],$product['id']));
if($query->rowCount()>0){$row=$query->fetch(\PDO::FETCH_ASSOC);$user_id=$row['id'];$user_referrer=$row['referrer'];}
				
			}
		
		}
		
		if($user_id!='0'){
			
$tokens_amount_referrer=$tokens_amount*$product['referral_percent']/100;
		    
$query = $db->prepare("SELECT id, status FROM payments WHERE transaction_id = ? AND product_id = ?");
$query->execute(array($_POST["txn_id"],$product['id']));
$record_exists=$query->rowCount();

if($record_exists<=0){

$query = $db->prepare("INSERT INTO payments (uid, transaction_id, amount, currency, status, status_text, tokens_amount, tokens_amount_bonus, system, time, product_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$query->execute(array($user_id,$_POST["txn_id"],intval($_POST["mc_gross"]),$_POST["mc_currency"],$status,$_POST["payment_status"],$tokens_amount,$tokens_amount_bonus,'paypal',time(),$product['id']));

$payment_id=$db->lastInsertId();


#Реферальная система
if($user_referrer!=''){
$query = $db->prepare("SELECT id FROM users WHERE id = ? AND product_id = ?");
$query->execute(array($user_referrer,$product['id']));
if($query->rowCount()>0){

$query = $db->prepare("INSERT INTO payments (uid, referral_transaction, transaction_id, amount, currency, status, status_text, tokens_amount, system, time, product_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$query->execute(array($user_referrer,$payment_id,$_POST["txn_id"],intval($_POST["mc_gross"]),$_POST["mc_currency"],$status,$_POST["payment_status"],$tokens_amount_referrer,'referrer',time(),$product['id']));
}else{$tokens_amount_referrer=0;}
}else{$tokens_amount_referrer=0;}


}else{

$row=$query->fetch(\PDO::FETCH_ASSOC);

if($row['status']=='100' && $status=='-1'){$action='-';}
if($row['status']=='100' && ($status=='1' || $status=='100')){$action='no';}
if($row['status']=='1' && $status=='100'){$action='+';}
if($row['status']=='1' && ($status=='1' || $status=='-1')){$action='no';}
if($row['status']=='-1' && $status=='100'){$action='+';}
if($row['status']=='-1' && ($status=='1' || $status=='-1')){$action='no';}

$query = $db->prepare("UPDATE payments SET status = ?, status_text = ? WHERE id = ? AND product_id = ?");
$query->execute(array_values(array($status,$_POST["payment_status"],$row['id'],$product['id'])));


#Реферальная система
if($user_referrer!=''){
$query = $db->prepare("SELECT id FROM users WHERE id = ? AND product_id = ?");
$query->execute(array($user_referrer,$product['id']));
if($query->rowCount()>0){

$query = $db->prepare("UPDATE payments SET status = ?, status_text = ? WHERE referral_transaction = ? AND product_id = ?");
$query->execute(array_values(array($status,$_POST["payment_status"],$row['id'],$product['id'])));
}else{$tokens_amount_referrer=0;}
}else{$tokens_amount_referrer=0;}

}

if($action=='+' || $action=='-'){

#Зачислить токены на счет покупателя
$query = $db->prepare("UPDATE users SET balance = (balance {$action} ?), balance_bonus = (balance_bonus {$action} ?) WHERE id = ? AND product_id = ?");
$query->execute(array_values(array($tokens_amount,$tokens_amount_bonus,$user_id,$product['id'])));

#Реферальная система
if($user_referrer!=''){
$query = $db->prepare("SELECT id FROM users WHERE id = ? AND product_id = ?");
$query->execute(array($user_referrer,$product['id']));
if($query->rowCount()>0){

#Зачислить бонусные токены на счет реферера
$query = $db->prepare("UPDATE users SET balance_referrer = (balance_referrer {$action} ?) WHERE id = ? AND product_id = ?");
$query->execute(array_values(array($tokens_amount_referrer,$user_referrer,$product['id'])));

}else{$tokens_amount_referrer=0;}
}else{$tokens_amount_referrer=0;}


#Обновить счетчик сбора USD, количество проданных токенов, бонусных токенов и реферальных токенов
$query = $db->prepare("UPDATE products SET usd_raised = (usd_raised {$action} ?), tokens_sold = (tokens_sold {$action} ?), tokens_bonus = (tokens_bonus {$action} ?), tokens_referrer = (tokens_referrer {$action} ?) WHERE id = ?");
$query->execute(array_values(array(intval($_POST["mc_gross"]),$tokens_amount,$tokens_amount_bonus,$tokens_amount_referrer,$product['id'])));
		}
		
    }

    }
} elseif ($enable_sandbox) {
    if ($_POST["test_ipn"] != 1) {
        $paypal_ipn_status = "RECEIVED FROM LIVE WHILE SANDBOXED";
    }
} elseif ($_POST["test_ipn"] == 1) {
    $paypal_ipn_status = "RECEIVED FROM SANDBOX WHILE LIVE";
}

if ($save_log_file) {
    // Create log file directory
    if (!is_dir($dated_log_file_dir)) {
        if (!file_exists($dated_log_file_dir)) {
            mkdir($dated_log_file_dir, 0777, true);
            if (!is_dir($dated_log_file_dir)) {
                $save_log_file = false;
            }
        } else {
            $save_log_file = false;
        }
    }
    // Restrict web access to files in the log file directory
    $htaccess_body = "RewriteEngine On" . "\r\n" . "RewriteRule .* - [L,R=404]";
    if ($save_log_file && (!is_file($log_file_dir . "/.htaccess") || file_get_contents($log_file_dir . "/.htaccess") !== $htaccess_body)) {
        if (!is_dir($log_file_dir . "/.htaccess")) {
            file_put_contents($log_file_dir . "/.htaccess", $htaccess_body);
            if (!is_file($log_file_dir . "/.htaccess") || file_get_contents($log_file_dir . "/.htaccess") !== $htaccess_body) {
                $save_log_file = false;
            }
        } else {
            $save_log_file = false;
        }
    }
    if ($save_log_file) {
        // Save data to text file
        file_put_contents($dated_log_file_dir . "/" . $test_text . "paypal_ipn_" . $date . ".txt", "paypal_ipn_status = " . $paypal_ipn_status . "\r\n" . "paypal_ipn_date = " . $timestamp . "\r\n" . $data_text . "\r\n", FILE_APPEND);
    }
}

if ($send_confirmation_email) {
    // Send confirmation email
    mail($confirmation_email_address, $test_text . "PayPal IPN : " . $paypal_ipn_status, "paypal_ipn_status = " . $paypal_ipn_status . "\r\n" . "paypal_ipn_date = " . $timestamp . "\r\n" . $data_text, "From: " . $from_email_address);
}

// Reply with an empty 200 response to indicate to paypal the IPN was received correctly
header("HTTP/1.1 200 OK");

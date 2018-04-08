$('document').ready(function(){
	
$("#two_factor_code").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl/cmd+A
            (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: Ctrl/cmd+C
            (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: Ctrl/cmd+X
            (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
	
$('.show-menu').on('click touch', function() {
	$('body').toggleClass('menu-opened')
});

$('.js-change-password').on('change', function() {
	$('.form__password').toggle();
	if($('input[name=pw]').val()=='1'){$('input[name=pw]').val('0');}else{$('input[name=pw]').val('1');}
});

$('.js-calc-minus').on('click touch', function() {

	var $val = $(this).parents('.js-calc-tab').find('.js-calc-input');
	var newValue = $val.val() - $val.data('step') ;
	if(newValue < 0) {
		newValue = 0
	} 

	$val.val(round(newValue));
	$val.change();
})

$('.js-calc-plus').on('click touch', function() {
	var $val = $(this).parents('.js-calc-tab').find('.js-calc-input');
	$val.val(round(+$val.val() + +$val.data('step')));
	$val.change();
})

$('.js-calc-input').on('change', function() {

	var $portal = $(this).parents('.js-calc-tab').find('.js-calc-portal');

	$portal.text(round($(this).val() * $('#calc_price').val()));
});

function round(val, p) {
	p = p || 10000000
	return Math.round(val * p) / p;
}


$('.js-calc-tabs a').on('shown.bs.tab', function (e) {
	$('.active .js-calc-input').focus();
});

 $("input[type='number']").keydown(function (e) {
	
	if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
         // Allow: Ctrl/cmd+A
        (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
         // Allow: Ctrl/cmd+C
        (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
         // Allow: Ctrl/cmd+X
        (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
         // Allow: home, end, left, right
        (e.keyCode >= 35 && e.keyCode <= 39)) {
             // let it happen, don't do anything
             return;
    }
    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
});
	
function validate_Email(sender_email) {
    var expression = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
    if (expression.test(sender_email)) {
        return true;
    } else {
        return false;
    }
}

$('#captcha-image').click(function() {
    $(this).attr('src', $(this).attr('src')+'?'+Math.random());
});

/* Sign in */
$('#signin-button').click(function(e) {
	
    e.preventDefault();
    e.stopImmediatePropagation();

var data = $('#signin-form').serialize();

$.ajax({
    type: 'POST',
    url: '/auth/',
    data: data,
    beforeSend: function() {
        $('#signin-button').prop('disabled', true);
		$('#signin-button').hide();
		$('#loading').show();
        $('#signin-email-error').hide();
        $('#signin-password-error').hide();
		$('#signin-error').hide();
    },
    success: function(result) {
        if (result.status == 'success' || result.status == 'signed_in') {document.location.reload(true);return true;}
		
        $('#signin-button').prop('disabled', false);
		$('#signin-button').show();
		$('#loading').hide();
		
        if (result.status == 'error') {
		$('#signin-error').html(result.message);
		$('#signin-error').show();
		}
		
        if (result.status == 'activation') {
		$('#signin-error').html(result.message);
		$('#signin-error').show();
		$('#activation-resend').bind( "click", {  foo: "bar"}, activation_resend );
		}
		
        if (result.status == 'email') {
            $('#signin-email-error').html(result.message);
            $('#signin-email-error').show();
        }
        if (result.status == 'password') {
            $('#signin-password-error').html(result.message);
            $('#signin-password-error').show();
        }

        console.log(result);
    },
    error: function() {
        console.log('Cannot retrieve data.');
    }
});
return false;
});

/* Reset password */
$('#reset-button').click(function(e) {
	
    e.preventDefault();
    e.stopImmediatePropagation();

var data = $('#reset-form').serialize();

$.ajax({
    type: 'POST',
    url: '/auth/',
    data: data,
    beforeSend: function() {
        $('#reset-button').prop('disabled', true);
		$('#reset-button').hide();
		$('#loading').show();
        $('#reset-email-error').hide();
		$('#reset-error').hide();
    },
    success: function(result) {
        if (result.status == 'signed_in') {document.location.reload(true);return true;}
		
        $('#reset-button').prop('disabled', false);
		$('#reset-button').show();
		$('#loading').hide();
		
        if (result.status == 'error') {
		$('#reset-error').html(result.message);
		$('#reset-error').show();
		}		
        if (result.status == 'success') {
		$('#reset-fields').hide();
		$('#reset-success').html(result.message);
		$('#reset-success').show();
		}
        if (result.status == 'email') {
            $('#reset-email-error').html(result.message);
            $('#reset-email-error').show();
        }
        console.log(result);
    },
    error: function() {
        console.log('Cannot retrieve data.');
    }
});
return false;
});

/* Change password with code */
$('#reset_code-button').click(function(e) {
	
    e.preventDefault();
    e.stopImmediatePropagation();

var data = $('#reset_code-form').serialize();

$.ajax({
    type: 'POST',
    url: '/auth/',
    data: data,
    beforeSend: function() {
        $('#reset_code-button').prop('disabled', true);
		$('#reset_code-button').hide();
		$('#reset_code-new_password-error').hide();
		$('#reset_code-new_password_confirmation-error').hide();
		$('#reset_code-error').hide();
		$('#loading').show();
    },
    success: function(result) {
		if (result.status == 'success' || result.status == 'signed_in') {document.location.reload(true);return true;}
		
        $('#reset_code-button').prop('disabled', false);
		$('#reset_code-button').show();
		$('#loading').hide();
		
        if (result.status == 'error') {
		$('#reset_code-error').html(result.message);
		$('#reset_code-error').show();
		}
        if (result.status == 'new_password') {
            $('#reset_code-new_password-error').html(result.message);
            $('#reset_code-new_password-error').show();
        }
        if (result.status == 'new_password_confirmation') {
            $('#reset_code-new_password_confirmation-error').html(result.message);
            $('#reset_code-new_password_confirmation-error').show();
        }
        console.log(result);
    },
    error: function() {
        console.log('Cannot retrieve data.');
    }
});
return false;
});

/* Two factor auth */
$('#two_factor-button').click(function(e) {
	
    e.preventDefault();
    e.stopImmediatePropagation();

var data = $('#two_factor-form').serialize();

$.ajax({
    type: 'POST',
    url: '/auth/',
    data: data,
    beforeSend: function() {
        $('#two_factor-button').prop('disabled', true);
		$('#two_factor-button').hide();
		$('#loading').show();
		$('#two_factor-error').hide();
    },
    success: function(result) {
        if (result.status == 'signed_in' || result.status == 'success') {document.location.reload(true);return true;}
		
        $('#two_factor-button').prop('disabled', false);
		$('#two_factor-button').show();
		$('#loading').hide();
		
        if (result.status == 'error') {
		$('#two_factor-error').html(result.message);
		$('#two_factor-error').show();
		$('#two_factor_code').val('');
		}		
        console.log(result);
    },
    error: function() {
        console.log('Cannot retrieve data.');
    }
});
return false;
});

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

/* Two factor settings */
$('#two_factor_settings-button').click(function(e) {
	
    e.preventDefault();
    e.stopImmediatePropagation();

var data = $('#two_factor_settings-form').serialize();

$.ajax({
    type: 'POST',
    url: '/request/',
    data: data,
    beforeSend: function() {
        $('#two_factor_settings-button').prop('disabled', true);
		$('#two_factor_settings-button').hide();
		$('#loading').show();
		$('#two_factor_settings-error').hide();
    },
    success: function(result) {
        if (result.status == 'not_signed_in' || result.status == 'success') {document.location.reload(true);return true;}
		
        $('#two_factor_settings-button').prop('disabled', false);
		$('#two_factor_settings-button').show();
		$('#loading').hide();
		
        if (result.status == 'error') {
		$('#two_factor_settings-error').html(result.message);
		$('#two_factor_settings-error').show();
		$('#two_factor_code').val('');
		}		
        console.log(result);
    },
    error: function() {
        console.log('Cannot retrieve data.');
    }
});
return false;
});

$("#button-invest" ).click(function(e) {
	
	e.preventDefault();
    e.stopImmediatePropagation();
	
	$('#invest-button').hide();
	$('#invest-select').show();
});

$("#currency" ).change(function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();
	
	$('#invest-purchase').hide();
	$('#invest-userwallets').hide();
	
	if($('#currency').val()=='SBD' || $('#currency').val()=='GOLOS' || $('#currency').val()=='BTS'){
		$('#userwallet_address').val($('#'+$('#currency').val()).val());
		if($('#currency').val()=='SBD'){$('#service_name').html('Steemit.com');}
		if($('#currency').val()=='GOLOS'){$('#service_name').html('Golos.io');}
		if($('#currency').val()=='BTS'){$('#service_name').html('Bitshares.org');}
		$('#invest-userwallets').show();
		return false;}
	
$.ajax({
    type: 'POST',
    url: '/request/',
    headers: {
      'Content-Type':'application/x-www-form-urlencoded'
    },
    data: {
      'do': 'deposit_address',
      'currency': $('#currency').val()
    },
    beforeSend: function() {
        $('#currency').prop('disabled', true);
		$('#loading').show();
    },
    success: function(result) {
        if (result.status == 'not_signed_in') {document.location.reload(true);return true;}
		
        $('#currency').prop('disabled', false);
		$('#loading').hide();
		
        if (result.status == 'success') {
            $('#deposit_address').val(result.message);
			$('#deposit_address_clipboard').attr('data-clipboard-text',result.message);
            $('#currency_name').html($('#currency').val());
            $('#currency_letters').html($('#currency').val());
            $('#currency_name_amount').html($('#currency').val());
            $('#currency_name_usd').html($('#currency').val());
            $('#currency_to_usd').html(result.currency_to_usd);
            $('#token_price').html(result.token_price);
			$('#calc_price').val((result.currency_to_usd/result.token_price));
			$('#currency_calc').val('0');
			$('#calc_result').html('0');
            $('#deposit_qr').attr("src","https://chart.googleapis.com/chart?chs=225x225&chld=L|2&cht=qr&chl=" + result.message);
			$('#invest-purchase').show();
        }
        console.log(result);
    },
    error: function() {
        console.log('Cannot retrieve data.');
    }
});
return false;
});

//User wallets
$('#userwallet_address-button').click(function(e) {
  
    e.preventDefault();
    e.stopImmediatePropagation();
	
	$('#invest-purchase').hide();
	$('#invest-userwallets').hide();
	
$.ajax({
    type: 'POST',
    url: '/request/',
    headers: {
      'Content-Type':'application/x-www-form-urlencoded'
    },
    data: {
      'do': 'userwallet',
      'wallet': $('#userwallet_address').val(),
      'currency': $('#currency').val()
    },
    beforeSend: function() {
		$('#userwallet_address-error').hide();
        $('#userwallet_address-button').prop('disabled', true);
		$('#loading').show();
    },
    success: function(result) {
        if (result.status == 'not_signed_in') {document.location.reload(true);return true;}
		
        $('#userwallet_address-button').prop('disabled', false);
		$('#loading').hide();
		
        if (result.status == 'success') {
            $('#deposit_address').val(result.message);
			$('#deposit_address_clipboard').attr('data-clipboard-text',result.message);
            $('#currency_name').html($('#currency').val());
            $('#currency_letters').html($('#currency').val());
            $('#currency_name_amount').html($('#currency').val());
            $('#currency_name_usd').html($('#currency').val());
            $('#currency_to_usd').html(result.currency_to_usd);
            $('#token_price').html(result.token_price);
			$('#calc_price').val((result.currency_to_usd/result.token_price));
			$('#currency_calc').val('0');
			$('#calc_result').html('0');
            $('#deposit_qr').attr("src","https://chart.googleapis.com/chart?chs=225x225&chld=L|2&cht=qr&chl=" + result.message);
			$('#invest-purchase').show();
			$('#'+$('#currency').val()).val($('#userwallet_address').val());
        }
		
        if (result.status == 'userwallet_address') {
            $('#userwallet_address-error').html(result.message);
            $('#userwallet_address-error').show();
            $('#invest-userwallets').show();
        }
        console.log(result);
    },
    error: function() {
        console.log('Cannot retrieve data.');
    }
});
return false;
});

/* Profile form */
$('#profile-button').click(function(e) {
	
    e.preventDefault();
    e.stopImmediatePropagation();

var data = $('#profile-form').serialize();

$.ajax({
    type: 'POST',
    url: '/request/',
    data: data,
    beforeSend: function() {
        $('#profile-button').prop('disabled', true);
		$('#profile-button').hide();
		$('#loading').show();
		$('#profile-success').hide();
		$('#profile-error').hide();
        $('#profile-ethereum-error').hide();
        $('#profile-current_password-error').hide();
        $('#profile-new_password-error').hide();
        $('#profile-new_password_confirmation-error').hide();
    },
    success: function(result) {		
        if (result.status == 'not_signed_in') {document.location.reload(true);return true;}
		
        $('#profile-button').prop('disabled', false);
		$('#profile-button').show();
		$('#loading').hide();
		
        if (result.status == 'success') {
            $('#profile-success').html(result.message);
            $('#profile-success').show();
			$("input[name=current_password]").val('');
			$("input[name=new_password]").val('');
			$("input[name=new_password_confirmation]").val('');
        }
        if (result.status == 'error') {
		$('#profile-error').html(result.message);
		$('#profile-error').show();
		}
        if (result.status == 'current_password') {
            $('#profile-current_password-error').html(result.message);
            $('#profile-current_password-error').show();
        }
        if (result.status == 'new_password') {
            $('#profile-new_password-error').html(result.message);
            $('#profile-new_password-error').show();
        }
        if (result.status == 'new_password_confirmation') {
            $('#profile-new_password_confirmation-error').html(result.message);
            $('#profile-new_password_confirmation-error').show();
        }
        if (result.status == 'ethereum') {
            $('#profile-ethereum-error').html(result.message);
            $('#profile-ethereum-error').show();
        }

        console.log(result);
    },
    error: function() {
        console.log('Cannot retrieve data.');
    }
});
return false;
});

/* Admin main form */
$('#admin_main-button').click(function(e) {
	
    e.preventDefault();
    e.stopImmediatePropagation();

var data = $('#admin_main-form').serialize();

$.ajax({
    type: 'POST',
    url: '/request/',
    data: data,
    beforeSend: function() {
        $('#admin_main-button').prop('disabled', true);
		$('#admin_main-button').hide();
		$('#admin_main-loading').show();
		$('#admin-success').hide();
		$('#admin-error').hide();
        $('#admin-name-error').hide();
        $('#admin-short_name-error').hide();
        $('#admin-url-error').hide();
        $('#admin-url_main-error').hide();
        $('#admin-user_agreement-error').hide();
        $('#admin-footer_copyright-error').hide();
        $('#admin-token_name-error').hide();
        $('#admin-smtp_host-error').hide();
        $('#admin-email-error').hide();
        $('#admin-smtp_password-error').hide();
        $('#admin-smtp_security-error').hide();
        $('#admin-smtp_port-error').hide();
        $('#admin-smartcontract_address-error').hide();
        $('#admin-smartcontract_creator-error').hide();
        $('#admin-coinpayments_public_key-error').hide();
        $('#admin-coinpayments_private_key-error').hide();
        $('#admin-coinpayments_ipn_secret-error').hide();
        $('#admin-coinpayments_merchant_id-error').hide();
        $('#admin-etherscan_api_key-error').hide();
        $('#admin-twitter_consumer_key-error').hide();
        $('#admin-twitter_consumer_secret-error').hide();
        $('#admin-facebook_app_id-error').hide();
        $('#admin-facebook_app_secret-error').hide();
        $('#admin-captcha_enabled-error').hide();
        $('#admin-google_oauth-error').hide();
        $('#admin-recaptcha_site_key-error').hide();
        $('#admin-recaptcha_secret-error').hide();
        $('#admin-bitcointalk-error').hide();
        $('#admin-facebook-error').hide();
        $('#admin-twitter-error').hide();
        $('#admin-telegram-error').hide();
        $('#admin-vk-error').hide();
        $('#admin-youtube-error').hide();
    },
    success: function(result) {
        if (result.status == 'not_signed_in') {document.location.reload(true);return true;}
		
        $('#admin_main-button').prop('disabled', false);
		$('#admin_main-button').show();
		$('#admin_main-loading').hide();
		
        if (result.status == 'success'){$('#admin-success').html(result.message);$('#admin-success').show();$('html, body').animate({scrollTop: 0}, 800);setTimeout(function() {$('#admin-success').hide();}, 5000);}
        if (result.status == 'error'){$('#admin-error').html(result.message);$('#admin-error').show();$('html, body').animate({scrollTop: 0}, 800);setTimeout(function() {$('#admin-error').hide();}, 5000);}
		
		if (result.status == 'name'){$('#admin-name-error').html(result.message);$('#admin-name-error').show();}
		if (result.status == 'short_name'){$('#admin-short_name-error').html(result.message);$('#admin-short_name-error').show();}
		if (result.status == 'url'){$('#admin-url-error').html(result.message);$('#admin-url-error').show();}
		if (result.status == 'url_main'){$('#admin-url_main-error').html(result.message);$('#admin-url_main-error').show();}
		if (result.status == 'user_agreement'){$('#admin-user_agreement-error').html(result.message);$('#admin-user_agreement-error').show();}
		if (result.status == 'footer_copyright'){$('#admin-footer_copyright-error').html(result.message);$('#admin-footer_copyright-error').show();}
		if (result.status == 'token_name'){$('#admin-token_name-error').html(result.message);$('#admin-token_name-error').show();}
		if (result.status == 'smtp_host'){$('#admin-smtp_host-error').html(result.message);$('#admin-smtp_host-error').show();}
		if (result.status == 'email'){$('#admin-email-error').html(result.message);$('#admin-email-error').show();}
		if (result.status == 'smtp_password'){$('#admin-smtp_password-error').html(result.message);$('#admin-smtp_password-error').show();}
		if (result.status == 'smtp_security'){$('#admin-smtp_security-error').html(result.message);$('#admin-smtp_security-error').show();}
		if (result.status == 'smtp_port'){$('#admin-smtp_port-error').html(result.message);$('#admin-smtp_port-error').show();}
		if (result.status == 'smartcontract_address'){$('#admin-smartcontract_address-error').html(result.message);$('#admin-smartcontract_address-error').show();}
		if (result.status == 'smartcontract_creator'){$('#admin-smartcontract_creator-error').html(result.message);$('#admin-smartcontract_creator-error').show();}
		if (result.status == 'coinpayments_public_key'){$('#admin-coinpayments_public_key-error').html(result.message);$('#admin-coinpayments_public_key-error').show();}
		if (result.status == 'coinpayments_private_key'){$('#admin-coinpayments_private_key-error').html(result.message);$('#admin-coinpayments_private_key-error').show();}
		if (result.status == 'coinpayments_ipn_secret'){$('#admin-coinpayments_ipn_secret-error').html(result.message);$('#admin-coinpayments_ipn_secret-error').show();}
		if (result.status == 'coinpayments_merchant_id'){$('#admin-coinpayments_merchant_id-error').html(result.message);$('#admin-coinpayments_merchant_id-error').show();}
		if (result.status == 'etherscan_api_key'){$('#admin-etherscan_api_key-error').html(result.message);$('#admin-etherscan_api_key-error').show();}
		if (result.status == 'twitter_consumer_key'){$('#admin-twitter_consumer_key-error').html(result.message);$('#admin-twitter_consumer_key-error').show();}
		if (result.status == 'twitter_consumer_secret'){$('#admin-twitter_consumer_secret-error').html(result.message);$('#admin-twitter_consumer_secret-error').show();}
		if (result.status == 'facebook_app_id'){$('#admin-facebook_app_id-error').html(result.message);$('#admin-facebook_app_id-error').show();}
		if (result.status == 'facebook_app_secret'){$('#admin-facebook_app_secret-error').html(result.message);$('#admin-facebook_app_secret-error').show();}
		if (result.status == 'captcha_enabled'){$('#admin-captcha_enabled-error').html(result.message);$('#admin-captcha_enabled-error').show();}
		if (result.status == 'google_oauth'){$('#admin-google_oauth-error').html(result.message);$('#admin-google_oauth-error').show();}
		if (result.status == 'recaptcha_site_key'){$('#admin-recaptcha_site_key-error').html(result.message);$('#admin-recaptcha_site_key-error').show();}
		if (result.status == 'recaptcha_secret'){$('#admin-recaptcha_secret-error').html(result.message);$('#admin-recaptcha_secret-error').show();}
		if (result.status == 'bitcointalk'){$('#admin-bitcointalk-error').html(result.message);$('#admin-bitcointalk-error').show();}
		if (result.status == 'facebook'){$('#admin-facebook-error').html(result.message);$('#admin-facebook-error').show();}
		if (result.status == 'twitter'){$('#admin-twitter-error').html(result.message);$('#admin-twitter-error').show();}
		if (result.status == 'telegram'){$('#admin-telegram-error').html(result.message);$('#admin-telegram-error').show();}
		if (result.status == 'vk'){$('#admin-vk-error').html(result.message);$('#admin-vk-error').show();}
		if (result.status == 'youtube'){$('#admin-youtube-error').html(result.message);$('#admin-youtube-error').show();}

        console.log(result);
    },
    error: function() {
        console.log('Cannot retrieve data.');
    }
});
return false;
});

/* Admin settings form */
$('#admin_settings-button').click(function(e) {
	
    e.preventDefault();
    e.stopImmediatePropagation();

var data = $('#admin_settings-form').serialize();

$.ajax({
    type: 'POST',
    url: '/request/',
    data: data,
    beforeSend: function() {
        $('#admin_settings-button').prop('disabled', true);
		$('#admin_settings-button').hide();
		$('#admin_settings-loading').show();
		$('#admin-success').hide();
		$('#admin-error').hide();
        $('#admin-token_price-error').hide();
        $('#admin-token_price_eth-error').hide();
        $('#admin-bonus_percent-error').hide();
        $('#admin-referral_percent-error').hide();
        $('#admin-signup_tokens-error').hide();
        $('#admin-support_email-error').hide();
        $('#admin-email_activation-error').hide();
        $('#admin-ethereum_field_required-error').hide();
        $('#admin-currency-error').hide();
        $('#admin-SBD_wallet-error').hide();
        $('#admin-GOLOS_wallet-error').hide();
        $('#admin-BTS_wallet-error').hide();
    },
    success: function(result) {
        if (result.status == 'not_signed_in') {document.location.reload(true);return true;}
		
        $('#admin_settings-button').prop('disabled', false);
		$('#admin_settings-button').show();
		$('#admin_settings-loading').hide();
		
        if (result.status == 'success'){$('#admin-success').html(result.message);$('#admin-success').show();$('html, body').animate({scrollTop: 0}, 800);setTimeout(function() {$('#admin-success').hide();}, 5000);}
        if (result.status == 'error'){$('#admin-error').html(result.message);$('#admin-error').show();$('html, body').animate({scrollTop: 0}, 800);setTimeout(function() {$('#admin-error').hide();}, 5000);}
		
		if (result.status == 'token_price'){$('#admin-token_price-error').html(result.message);$('#admin-token_price-error').show();}
		if (result.status == 'token_price_eth'){$('#admin-token_price_eth-error').html(result.message);$('#admin-token_price_eth-error').show();}
		if (result.status == 'bonus_percent'){$('#admin-bonus_percent-error').html(result.message);$('#admin-bonus_percent-error').show();}
		if (result.status == 'referral_percent'){$('#admin-referral_percent-error').html(result.message);$('#admin-referral_percent-error').show();}
		if (result.status == 'signup_tokens'){$('#admin-signup_tokens-error').html(result.message);$('#admin-signup_tokens-error').show();}
		if (result.status == 'support_email'){$('#admin-support_email-error').html(result.message);$('#admin-support_email-error').show();}
		if (result.status == 'email_activation'){$('#admin-email_activation-error').html(result.message);$('#admin-email_activation-error').show();}
		if (result.status == 'ethereum_field_required'){$('#admin-ethereum_field_required-error').html(result.message);$('#admin-ethereum_field_required-error').show();}
		if (result.status == 'currency'){$('#admin-currency-error').html(result.message);$('#admin-currency-error').show();}
		if (result.status == 'SBD_wallet'){$('#admin-SBD_wallet-error').html(result.message);$('#admin-SBD_wallet-error').show();}
		if (result.status == 'GOLOS_wallet'){$('#admin-GOLOS_wallet-error').html(result.message);$('#admin-GOLOS_wallet-error').show();}
		if (result.status == 'BTS_wallet'){$('#admin-BTS_wallet-error').html(result.message);$('#admin-BTS_wallet-error').show();}

        console.log(result);
    },
    error: function() {
        console.log('Cannot retrieve data.');
    }
});
return false;
});

/* Admin user search */
$('#admin_search-button').click(function(e) {
	
    e.preventDefault();
    e.stopImmediatePropagation();

var data = $('#admin_search-form').serialize();

$.ajax({
    type: 'POST',
    url: '/request/',
    data: data,
    beforeSend: function() {
        $('#admin_search-button').prop('disabled', true);
		$('#admin_search-button').hide();
		$('#admin_search-loading').show();
        $('#admin-user_email-error').hide();
    },
    success: function(result) {
        $('#admin_search-button').prop('disabled', false);
		$('#admin_search-button').show();
		$('#admin_search-loading').hide();
		
        if (result.status == 'success') {
		$('#user_id').val(result.user_id);
		$('#user_email').val('');
		$('#admin-success').html(result.message);$('#admin-success').show();setTimeout(function() {$('#admin-success').hide();}, 10000);
		return true;}
        if (result.status == 'user_email') {
		$('#admin-user_email-error').html(result.message);
		$('#admin-user_email-error').show();
		}

        console.log(result);
    },
    error: function() {
        console.log('Cannot retrieve data.');
    }
});
return false;
});

/* Admin add tokens */
$('#admin_tokens-button').click(function(e) {
	
    e.preventDefault();
    e.stopImmediatePropagation();

var data = $('#admin_tokens-form').serialize();

$.ajax({
    type: 'POST',
    url: '/request/',
    data: data,
    beforeSend: function() {
        $('#admin_tokens-button').prop('disabled', true);
		$('#admin_tokens-button').hide();
		$('#admin_tokens-loading').show();
        $('#admin-user_id-error').hide();
        $('#admin-currency_-error').hide();
        $('#admin-amount-error').hide();
        $('#admin-tokens_amount-error').hide();
        $('#admin-act-error').hide();
        $('#admin-comment-error').hide();
		$('#admin-success').hide();
    },
    success: function(result) {
        $('#admin_tokens-button').prop('disabled', false);
		$('#admin_tokens-button').show();
		$('#admin_tokens-loading').hide();
		
        if (result.status == 'success') {
		$('#user_id').val('');
		$('#currency_').val('');
		$('#amount').val('');
		$('#tokens_amount').val('');
		$('#comment').val('');
		$('#admin-success').html(result.message);$('#admin-success').show();$('html, body').animate({scrollTop: 0}, 800);setTimeout(function() {$('#admin-success').hide();}, 5000);
		return true;}
        if (result.status == 'user_id') {
		$('#admin-user_id-error').html(result.message);
		$('#admin-user_id-error').show();
		}
		if (result.status == 'currency') {
		$('#admin-currency_-error').html(result.message);
		$('#admin-currency_-error').show();
		}	
        if (result.status == 'amount') {
		$('#admin-amount-error').html(result.message);
		$('#admin-amount-error').show();
		}
        if (result.status == 'tokens_amount') {
		$('#admin-tokens_amount-error').html(result.message);
		$('#admin-tokens_amount-error').show();
		}
        if (result.status == 'act') {
        $('#admin-act-error').html(result.message);
        $('#admin-act-error').show();
        }
        if (result.status == 'comment') {
        $('#admin-comment-error').html(result.message);
        $('#admin-comment-error').show();
        }

        console.log(result);
    },
    error: function() {
        console.log('Cannot retrieve data.');
    }
});
return false;
});

/* Support form */
$('#support-button').click(function(e) {
	
    e.preventDefault();
    e.stopImmediatePropagation();

var data = $('#support-form').serialize();

$.ajax({
    type: 'POST',
    url: '/request/',
    data: data,
    beforeSend: function() {
        $('#support-button').prop('disabled', true);
		$('#support-button').hide();
		$('#support-loading').show();
		$('#support-success').hide();
		$('#support-error').hide();
    },
    success: function(result) {
        if (result.status == 'not_signed_in') {document.location.reload(true);return true;}
		
        $('#support-button').prop('disabled', false);
		$('#support-button').show();
		$('#support-loading').hide();
		
        if (result.status == 'success'){$('#support-success').html(result.message);$('#support-success').show();$('#support-form')[0].reset();}
        if (result.status == 'error'){$('#support-error').html(result.message);$('#support-error').show();}

        console.log(result);
    },
    error: function() {
        console.log('Cannot retrieve data.');
    }
});
return false;
});

/* Transaction info */
$('.txinfo').click(function() {
$('#tx-time').html($(this).data('time'));
$('#tx-system').html($(this).data('system'));
$('#tx-id').html($(this).data('id'));
$('#tx-status').html($(this).data('status'));
$('#tx-status').removeClass('green red yellow');
$('#tx-status').addClass($(this).data('color'));
if($(this).data('bonus')=='0'){$('#tx-bonus-div').hide();}else{$('#tx-bonus-div').show();}
$('#tx-bonus').html($(this).data('bonus'));
$('#tx-tokens').html($(this).data('tokens'));
$('#tx-amount').html($(this).data('amount'));
});

});
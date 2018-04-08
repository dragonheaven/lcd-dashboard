<?php if(!isset($settings)){header('location: /');exit;}?><!DOCTYPE html>
<html lang="<?=$lng['locale_lng']?>">

<?php    
    header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
    header("Pragma: no-cache"); // HTTP 1.0.
    header("Expires: 0"); // Proxies.
?>

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no" />
	<title><?=$product['name']?></title>
	<meta name="description" content="<?=$lng['meta_description']?>">
	<meta name="format-detection" content="telephone=no" />
	<link rel="stylesheet" href="/assets/products/<?=$product['id']?>/css.css?v=1.0" />
	
	<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-W2P3BWM');</script>
<!-- End Google Tag Manager -->

<!-- Yandex.Metrika counter --> <script type="text/javascript" > (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter46644159 = new Ya.Metrika({ id:46644159, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks"); </script> <noscript><div><img src="https://mc.yandex.ru/watch/46644159" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->
	
	<script src="/assets/js/jquery-3.2.1.min.js"></script>
    <!-- Hotjar Tracking Code for lucyd.co -->
<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:710060,hjsv:6};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
</script>
</head>

<body>
    <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-W2P3BWM"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
	<nav class="top">
		<div class="container">
			<div class="top__right">
				<div class="top__block currency">
					<div class="currency__left">
						<div class="currency__btc">1 <?=$product['token_name']?></div>
						<div class="currency__portal">= <?php if($product['token_price_eth']!='0' && $product['token_price_eth']>0){echo $product['token_price_eth'].' ETH';}else{echo $product['token_price'].'$';}?></div>
					</div>
				</div>
				<ul class="menu top__block">
					<?php if($user['admin']=='1'){echo'<li class="menu__item"><a class="menu__link';if($module=='admin'){echo' menu__link--active';}echo'" href="/admin/">'.$lng['admin_panel'].'</a></li>';}?>
					<li class="menu__item"><a class="menu__link<?php if($module=='dashboard'){echo' menu__link--active';}?>" href="/"><?=$lng['menu_main']?></a></li>
					<li class="menu__item"><a class="menu__link<?php if($module=='profile'){echo' menu__link--active';}?>" href="/profile/"><?=$lng['menu_settings']?></a></li>
				</ul>
				<div class="top__block top__dropdown">
					<div class="login">
						<div class="login__avatar">
							<img src="/assets/products/<?=$product['id']?>/avatar@2x.png" width="31" alt="" />
						</div>
						<div class="menu__item menu__item--mobile"><a class="menu__link" href="/signout/<?=$_SESSION['authID']?>/"><?=$lng['auth_signout']?></a>
						</div><span class="login__email"><?=$user['email']?></span>
					</div>
					<div class="dropdown__content">
						<div class="dropdown__title"><?=$lng['token_balance']?></div>
						<div class="portal-value"><?=round(($user['balance']+$user['balance_bonus']+$user['balance_referrer']),4)?></div>
						<div>
							<?php if($user['admin']=='1'){echo'<div class="dropdown__row"> <a class="dropdown__link dropdown__link--admin" href="/admin/">'.$lng['admin_panel'].'</a></div>';}?>
							<div class="dropdown__row"> <a class="dropdown__link dropdown__link--settings" href="/profile/"><?=$lng['menu_profile']?></a></div>
							<div class="dropdown__row"> <a class="dropdown__link dropdown__link--support" href="/support/"><?=$lng['menu_support']?></a></div>
							<div class="dropdown__row"> <a class="dropdown__link dropdown__link--logout" href="/signout/<?=$_SESSION['authID']?>/"><?=$lng['auth_signout']?></a></div>
						</div>
					</div>
				</div>
				<div class="currency currency--mobile clearfix">
					<div class="currency__left">
						<div class="currency__btc">1 <?=$product['token_name']?></div>
						<div class="currency__portal">= <?php if($product['token_price_eth']!='0' && $product['token_price_eth']>0){echo $product['token_price_eth'].' ETH';}else{echo $product['token_price'].'$';}?></div>
					</div>
				</div>
				<div class="langs top__block"><a class="langs__link langs__link--current" href="/<?=$lng['language_code']?>/"><?=$lng['language_code']?></a>
					<div class="langs__hidden"><a class="langs__link<?php if($lng['language_code']=='en'){echo' langs__link--active';}?>" href="/en/">en</a><a class="langs__link<?php if($lng['language_code']=='ru'){echo' langs__link--active';}?>" href="/ru/">ru</a><a class="langs__link<?php if($lng['language_code']=='ja'){echo' langs__link--active';}?>" href="/ja/">ja</a><a class="langs__link<?php if($lng['language_code']=='ko'){echo' langs__link--active';}?>" href="/ko/">ko</a><a class="langs__link<?php if($lng['language_code']=='cn'){echo' langs__link--active';}?>" href="/cn/">cn</a></div>
				</div>
				<div class="top__copy"><?=$product['footer_copyright']?> <img src="/assets/products/<?=$product['id']?>/logo_small@2x.png" width="69" alt="" /></div>
			</div>
			<a class="logo" href="<?=$product['url_main']?>" target="_blank"><img src="/assets/products/<?=$product['id']?>/logo.png" srcset="/assets/products/<?=$product['id']?>/logo@2x.png 2x" alt="" /></a>
			<div class="login__avatar login__avatar--mobile">
				<img src="/assets/products/<?=$product['id']?>/avatar@2x.png" width="31" alt="" />
			</div><a class="show-menu" href="#"><span></span></a>
		</div>
	</nav>
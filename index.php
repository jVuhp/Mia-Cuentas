<!DOCTYPE HTML>

<?php
/*
===========================================================================

	Powered by: DevByBit
	Site: devbybit.com
	Date: 4/17/2024 9:26 AM
	Author: Vuhp
	Documentation: docs.devbybit.com

===========================================================================
*/

session_start();
if (file_exists('function.php')) {
	require_once('function.php');
} else {
	require_once('../function.php');
}

$uri = $_SERVER['REQUEST_URI'];
$uri = str_replace('/test/', '', $uri);
$uri = ltrim($uri, '/');
$page = explode('/', $uri);
$codeAuth = explode('?', $uri);

if ($uri === '' || substr($uri, -1) === '/') {
    $archivo = __DIR__ . '/views/index.php';
} else {
    $archivo = __DIR__ . '/views/' . $uri . '.php';
}

$adminSQL = $connx->prepare("SELECT * FROM `mcs_admin` WHERE `g_id` = ? AND `status` = '1';");
$adminSQL->execute([$_SESSION['mcs_user']['g_id']]);
$admin_verify = ($adminSQL->RowCount() > 0) ? 1 : 0;


if (!isset($_SESSION['mcs_wallet'])) {
	$walletSQL = $connx->prepare("SELECT * FROM `mcs_wallet_user` WHERE `user` = ? ORDER BY id ASC;");
	$walletSQL->execute([$_SESSION['mcs_user']['id']]);
	$wallets = $walletSQL->fetch(PDO::FETCH_ASSOC);
	
	if ($walletSQL->RowCount() > 0) {
		$_SESSION['mcs_wallet'] = [
			'id' => $wallets['id'],
			'name' => $wallets['name'],
			'options' => $wallets['options'],
			'status' => $wallets['status'],
		];
	}
}

if (isset($_SESSION['mcs_user'])) {
	
	$userSQLs = $connx->prepare("SELECT * FROM `mcs_user` WHERE `id` = ?;");
	$userSQLs->execute([$_SESSION['mcs_user']['id']]);
	$user = $userSQLs->fetch(PDO::FETCH_ASSOC);
	
	$options_user = explode(', ', $user['options']);
	
	$walletSQL = $connx->prepare("SELECT * FROM `mcs_wallet` WHERE `id` = ?;");
	$walletSQL->execute([$_SESSION['mcs_wallet']['id']]);
	$wallet = $walletSQL->fetch(PDO::FETCH_ASSOC);
	
	$walletCountSQL = $connx->prepare("SELECT COUNT(id) as total FROM `mcs_wallet_user` WHERE `user` = ?;");
	$walletCountSQL->execute([$_SESSION['mcs_user']['id']]);
	$walletCount = $walletCountSQL->fetch(PDO::FETCH_ASSOC);
	$wallet_quant = $walletCount['total'];
}

$chars_wallet = 'ASDFGHJKLPOIUYTREWQZXCVBNM0987654321asdfghjklpoiuytrewqzxcvbnm0123456789';

//echo randomCodes(6, $chars_wallet) . '-' . randomCodes(10, $chars_wallet) . '-' . randomCodes(18, $chars_wallet) . '-' . randomCodes(11, $chars_wallet);

if ($_SESSION['mcs_user']['logged']) {
	if ($user['status'] == 2) {
		if ($page[0] != 'suspended') {
			echo '<script>location.href ="' . URI . '/suspended";</script>';
		}
	} else if ($user['status']) {
		if ($page[0] == '' OR $page[0] == 'auth' OR $page[0] == 'pending') {
			echo '<script>location.href ="' . URI . '/wallet";</script>';
		}
	} else {
		if ($page[0] != 'pending') {
			echo '<script>location.href ="' . URI . '/pending";</script>';
		}
	}
	if (!isset($_COOKIE['mcs_user'])) {
		setcookie('mcs_logged', true, time() + 3600*24*30, '/');
		setcookie('mcs_user', $user['g_id'], time() + 3600*24*30, '/');
	}
} else {
	if ($page[0] != 'auth') {
		if ($page[0] != '' AND !$user['status']) {
			echo '<script>location.href ="' . URI . '";</script>';
		}
	}
	
	if ($_COOKIE['mcs_logged']) {
		
		$userSQLs = $connx->prepare("SELECT * FROM `mcs_user` WHERE `g_id` = ?;");
		$userSQLs->execute([$_COOKIE['mcs_user']]);
		$user = $userSQLs->fetch(PDO::FETCH_ASSOC);
		
		$_SESSION['mcs_user'] = [
			'id' => $user['id'],
			'g_id' => $user['g_id'],
			'name' => $user['name'],
			'email' => $user['email'],
			'status' => $user['status'],
			'avatar' => $user['avatar'],
			'logged' => true
		];
	}
}

if (!isset($_SESSION['mcs_wallet']) AND $page[0] == 'cuentas') {
	echo '<script>location.href ="' . URI . '/wallet";</script>';
}

$infinity_symbol = '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="text-success icon icon-tabler icons-tabler-outline icon-tabler-infinity"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9.828 9.172a4 4 0 1 0 0 5.656a10 10 0 0 0 2.172 -2.828a10 10 0 0 1 2.172 -2.828a4 4 0 1 1 0 5.656a10 10 0 0 1 -2.172 -2.828a10 10 0 0 0 -2.172 -2.828" /></svg>';

if (isset($_SESSION['mcs_user'])) {
	$invite = explode('=', $codeAuth[1]);
	if ($invite[0] == 'invite') {
		parse_str($codeAuth[1], $queryParams);

		$code = $queryParams['invite'];
		
		
		$walletSQL = $connx->prepare("SELECT * FROM `mcs_wallet` WHERE `uuid` = ?;");
		$walletSQL->execute([$code]);
		$wallet = $walletSQL->fetch(PDO::FETCH_ASSOC);
		
		$walletUserSQL = $connx->prepare("SELECT * FROM `mcs_wallet_user` WHERE `wallet` = ? AND `user` = ?;");
		$walletUserSQL->execute([$wallet['id'], $_SESSION['mcs_user']['id']]);
		if ($walletUserSQL->RowCount() == 0) {
			$userWalletSQL = $connx->prepare("INSERT INTO `mcs_wallet_user`(`wallet`, `user`) VALUES (?, ?);");
			$userWalletSQL->execute([$wallet['id'], $_SESSION['mcs_user']['id']]);
			echo '<script>location.href ="' . URI . '/wallet";</script>';
		}
		
		
	}
}
?>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
	<meta content="DevByBit" name="basetitle">
	<title><?php echo lang($messages, 'title', 'name'); ?> | DevByBit</title>
	<meta name="title" content="Mia Accounts - DevByBit" />
	
    <link rel="icon" href="<?php echo IMAGE_ICON; ?>" type="image/x-icon" />
	
	<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
		
    <link href="https://devbybit.com/demos/tablerio/dist/css/tabler.min.css?1684106062" rel="stylesheet"/>
    <link href="https://devbybit.com/demos/tablerio/dist/css/tabler-flags.min.css?1684106062" rel="stylesheet"/>
    <link href="https://devbybit.com/demos/tablerio/dist/css/tabler-payments.min.css?1684106062" rel="stylesheet"/>
    <link href="https://devbybit.com/demos/tablerio/dist/css/tabler-vendors.min.css?1684106062" rel="stylesheet"/>
    <link href="https://devbybit.com/demos/tablerio/dist/css/demo.min.css?1684106062" rel="stylesheet"/>
    <link href="https://devbybit.com/demos/tablerio/dist/libs/dropzone/dist/dropzone.css?1684106062" rel="stylesheet"/>
    <script src="https://devbybit.com/demos/tablerio/dist/libs/dropzone/dist/dropzone-min.js?1684106062" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
	<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
	<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
	<script>
	var site_domain = '<?php echo URI; ?>';
	var URI = '<?php echo URI; ?>';
	document.addEventListener('DOMContentLoaded', function() {
	
		window.dropAccount = function() {
			$.ajax({
				type: "POST",
				url: site_domain + '/execute/action.php',
				data: { result: 'logout' },
				success: function(response) {
					location.reload();
				}
			});
		}
		
		$('.languages').on('click', function(e) {
			e.preventDefault();
			var dataId = $(this).data('id');
			var result = 'change_lang';
			$.post(site_domain + '/execute/action.php', { result: result, data_id: dataId }, function(response) {
				var jsonData = JSON.parse(response);
				if (jsonData.type == 'success') {
					location.reload();
				}
			});
		});
	});
	function str_replace(var1, var2, text) {
		var regex = new RegExp(var1, 'g');
		var newText = text.replace(regex, var2);
		return newText;
	}
	function str_replaces(var1, var2, text) {
		for (var i = 0; i < var1.length; i++) {
			var regex = new RegExp(var1[i], 'g');
			text = text.replace(regex, var2[i]);
		}
		return text;
	}
	
	function copyText(text) {
		var input = document.createElement('input');
		input.setAttribute('value', text);
		document.body.appendChild(input);
		input.select();
		var result = document.execCommand('copy');
		document.body.removeChild(input);
		swal('Copied correctly!', '', 'success');
		return result;
	}
	</script>
    <style>
      @import url('https://rsms.me/inter/inter.css');
      :root {
      	--tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
      }
      body {
      	font-feature-settings: "cv03", "cv04", "cv11";
      }
	  
.search-container {
  position: relative;
}

.search-results {
	list-style-type: none;
	padding: 0;
	margin: 0;
	position: absolute;
	top: 100%;
	width: 100%;
	background: radial-gradient(at center top, #191919, #0f0f0f);
	box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
	display: none;
	z-index: 99;
}

.search-results li {
	margin: 5px;
	padding: 10px;
	border: 1px solid rgba(153,153,153,1) 75%);
	cursor: pointer;
}

.search-results li:hover {
	background: linear-gradient(225deg, #2e4cb01f, #2f74dc36);
}
    </style>
	
  </head>
  <body class="">
    <script src="https://devbybit.com/demos/tablerio/dist/js/demo-theme.min.js?1684106062"></script>
    <div class="page">
		<?php if ($page[0] != '' AND $page[0] != 'pending' AND $page[0] != 'suspended') { ?>
		<header class="navbar navbar-expand-md d-print-none">
			<div class="container-xl">
			  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			  </button>
			  <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
				<a href=".">
				  <img src="<?php echo IMAGE_ICON_LIGHT; ?>" height="32" alt="DevByBit.com" class="hide-theme-light">
				  <img src="<?php echo IMAGE_ICON_DARK; ?>" height="32" alt="DevByBit.com" class="hide-theme-dark">
				</a>
			  </h1>
			  <div class="navbar-nav flex-row order-md-last">
				<div class="d-none d-md-flex" style="margin-right: 5px;">
					<a href="?theme=dark" class="nav-link px-0 hide-theme-dark" data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="Enable dark mode" data-bs-original-title="<?php echo lang($messages, 'theme', 'mode', 'dark'); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z"></path></svg>
					</a>
					<a href="?theme=light" class="nav-link px-0 hide-theme-light" data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="Enable light mode" data-bs-original-title="<?php echo lang($messages, 'theme', 'mode', 'light'); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path><path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7"></path></svg>
					</a>
				  
					<div class="nav-item dropdown">
					  <a href="#" class="nav-link lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
						<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-language" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 5h7" /><path d="M9 3v2c0 4.418 -2.239 8 -5 8" /><path d="M5 9c0 2.144 2.952 3.908 6.7 4" /><path d="M12 20l4 -9l4 9" /><path d="M19.1 18h-6.2" /></svg>
					  </a>
					  <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
						
						<?php
						
						foreach (langList() as $list_lang) {
							$select = ($list_lang['data'] == $_SESSION['lang']) ? 'active' : '';
							echo '<a href="#" class="languages dropdown-item ' . $select . '" data-id="' . $list_lang['data'] . '">';
							echo '<span class="flag flag-country-' . $list_lang['flag'] . ' dropdown-item-icon"></span>';
							echo $list_lang['name'];
							echo '</a>';
						}
						
						?>
					  </div>
					</div>
				</div>
				<div class="nav-item dropdown">
				  <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
					<span class="avatar avatar-sm" style="background-image: url(<?php echo (isset($_SESSION['mcs_user']['avatar'])) ? $user['avatar'] : without_image; ?>);"></span>
					<div class="d-none d-xl-block ps-2">
					  <div class="mt-1 small text-secondary"><?php echo lang($messages, 'dropdown', 'logged_in'); ?></div>
					  <div><?php echo (isset($_SESSION['mcs_user']['name'])) ? $user['name'] : 'Unknown'; ?></div>
					</div>
				  </a>
				  <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
					<a href="?theme=dark" class="dropdown-item d-md-none hide-theme-dark"><?php echo lang($messages, 'dropdown', 'toggle_theme'); ?></a>
					<a href="?theme=light" class="dropdown-item d-md-none hide-theme-light"><?php echo lang($messages, 'dropdown', 'toggle_theme'); ?></a>
					<a href="select-lang" data-bs-toggle="modal" data-bs-target="#select-lang" class="dropdown-item d-md-none"><?php echo lang($messages, 'dropdown', 'select_lang'); ?></a>
					<a href="logout" onclick="event.preventDefault(); dropAccount();" class="dropdown-item"><?php echo lang($messages, 'dropdown', 'logout'); ?></a>
				  </div>
				</div>
			  </div>
			  <div class="collapse navbar-collapse" id="navbar-menu">
                      <ul class="navbar-nav">
                        <li class="nav-item <?php echo ($page[0] == 'wallet' OR $codeAuth[0] == 'wallet') ? 'active' : ''; ?>">
                          <a class="nav-link" href="<?php echo URI; ?>/wallet">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
								<?php echo lang($messages, 'navbar', 'wallet', 'icon'); ?>
                            </span>
                            <span class="nav-link-title">
                              <?php echo lang($messages, 'navbar', 'wallet', 'text'); ?>
                            </span>
                          </a>
                        </li>
						<?php if (isset($_SESSION['mcs_wallet'])) { ?>
                        <li class="nav-item <?php echo ($page[0] == 'cuentas' OR $codeAuth[0] == 'cuentas') ? 'active' : ''; ?>">
                          <a class="nav-link" href="<?php echo URI; ?>/cuentas">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
								<?php echo lang($messages, 'navbar', 'cuentas', 'icon'); ?>
                            </span>
                            <span class="nav-link-title">
                              <?php echo lang($messages, 'navbar', 'cuentas', 'text'); ?>
                            </span>
                          </a>
                        </li>
						<?php } ?>
						<?php if ($admin_verify) { ?>
                        <li class="nav-item <?php echo ($page[0] == 'admin' OR $codeAuth[0] == 'admin') ? 'active' : ''; ?>">
                          <a class="nav-link" href="<?php echo URI; ?>/admin">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
								<?php echo lang($messages, 'navbar', 'admin', 'icon'); ?>
                            </span>
                            <span class="nav-link-title">
                              <?php echo lang($messages, 'navbar', 'admin', 'text'); ?>
                            </span>
                          </a>
                        </li>
						<?php } ?>
                      </ul>
                </div>
			</div>
		</header>
		<?php
		if ($page[0] == 'wallet' OR $codeAuth[0] == 'wallet' OR $page[0] == 'cuentas' OR $codeAuth[0] == 'cuentas' OR $page[0] == 'admin' OR $codeAuth[0] == 'admin') {
		?>
		<header class="navbar-expand-md">
			<div class="collapse navbar-collapse" id="navbar-menu">
			  <div class="navbar">
				<div class="container-xl">
				<?php
				
				if ($page[0] == 'wallet' OR $codeAuth[0] == 'wallet') {
				
				?>
				  <div class="row flex-fill align-items-center">
					<div class="col">
					  <ul class="navbar-nav">
						<li class="nav-item <?php echo ($page[0] == 'wallet' AND $page[1] == 'new') ? 'active' : ''; ?>">
						  <a class="nav-link" href="<?php echo URI; ?>/wallet/new" data-bs-toggle="modal" data-bs-target="#create-wallet">
							<span class="nav-link-icon d-md-none d-lg-inline-block">
								<?php echo lang($messages, 'navbar', 'wallet', 'sub_nav', 'create', 'icon'); ?>
							</span>
							<span class="nav-link-title"><?php echo lang($messages, 'navbar', 'wallet', 'sub_nav', 'create', 'text'); ?></span>
						  </a>
						</li>
						
					  </ul>
					</div>
					<div class="col-2 d-none d-xxl-block"></div>
				  </div>
				<?php
				} else if ($page[0] == 'cuentas' OR $codeAuth[0] == 'cuentas') {
				?>
				
				  <div class="row flex-fill align-items-center">
					<div class="col">
					  <ul class="navbar-nav">
						<?php 
						
						$walletOwnerSQL = $connx->prepare("SELECT * FROM `mcs_wallet_user` WHERE `wallet` = ? ORDER BY id ASC;");
						$walletOwnerSQL->execute([$_SESSION['mcs_wallet']['id']]);
						$walletOwner = $walletOwnerSQL->fetch(PDO::FETCH_ASSOC);
						if ($walletOwner['user'] == $_SESSION['mcs_user']['id']) {
						
						?>
						<li class="nav-item <?php echo ($page[0] == 'wallet' AND $page[1] == 'kick') ? 'active' : ''; ?>">
						  <a class="nav-link" href="<?php echo URI; ?>/cuentas/kick" data-bs-toggle="modal" data-bs-target="#listDeleteUser">
							<span class="nav-link-icon d-md-none d-lg-inline-block">
								<?php echo lang($messages, 'navbar', 'cuentas', 'sub_nav', 'kick', 'icon'); ?>
							</span>
							<span class="nav-link-title"><?php echo lang($messages, 'navbar', 'cuentas', 'sub_nav', 'kick', 'text'); ?></span>
						  </a>
						</li>
						<li class="nav-item <?php echo ($page[0] == 'wallet' AND $page[1] == 'invite') ? 'active' : ''; ?>">
						  <a class="nav-link" href="<?php echo URI; ?>/cuentas/invite" data-bs-toggle="modal" data-bs-target="#inviteCopy" onclick="copyText('<?php echo URI; ?>?invite=<?php echo $wallet['uuid']; ?>');">
							<span class="nav-link-icon d-md-none d-lg-inline-block">
								<?php echo lang($messages, 'navbar', 'cuentas', 'sub_nav', 'invite', 'icon'); ?>
							</span>
							<span class="nav-link-title"><?php echo lang($messages, 'navbar', 'cuentas', 'sub_nav', 'invite', 'text'); ?></span>
						  </a>
						</li>
						<?php
						
						}
						
						?>
						<li class="nav-item <?php echo ($page[0] == 'wallet' AND $page[1] == 'new') ? 'active' : ''; ?>">
						  <a class="nav-link" href="<?php echo URI; ?>/cuentas/new" data-bs-toggle="modal" data-bs-target="#modal-report">
							<span class="nav-link-icon d-md-none d-lg-inline-block">
								<?php echo lang($messages, 'navbar', 'cuentas', 'sub_nav', 'create', 'icon'); ?>
							</span>
							<span class="nav-link-title"><?php echo lang($messages, 'navbar', 'cuentas', 'sub_nav', 'create', 'text'); ?></span>
						  </a>
						</li>
						
					  </ul>
					</div>
					<div class="col-2 d-none d-xxl-block"></div>
				  </div>
				<?php
				} else if ($page[0] == 'admin' OR $codeAuth[0] == 'admin') {
					
					$requestTotal = 0;
					$accPendingSQL = $connx->prepare("SELECT COUNT(id) AS total FROM `mcs_user` WHERE `status` = '0';");
					$accPendingSQL->execute();
					$accPending = $accPendingSQL->fetch(PDO::FETCH_ASSOC);
					$requestTotal += $accPending['total'];
					
					$wallPendingSQL = $connx->prepare("SELECT COUNT(id) AS total FROM `mcs_wallet` WHERE `status` = '0';");
					$wallPendingSQL->execute();
					$wallPending = $wallPendingSQL->fetch(PDO::FETCH_ASSOC);
					$requestTotal += $wallPending['total'];
				?>
				
				  <div class="row flex-fill align-items-center">
					<div class="col">
					  <ul class="navbar-nav">
					  
						<li class="nav-item <?php echo ($page[0] == 'admin' AND $page[1] == '' OR $codeAuth[0] == 'admin') ? 'active' : ''; ?>">
						  <a class="nav-link" href="<?php echo URI; ?>/admin">
							<span class="nav-link-icon d-md-none d-lg-inline-block">
								<?php echo lang($messages, 'navbar', 'admin', 'sub_nav', 'index', 'icon'); ?>
							</span>
							<span class="nav-link-title"><?php echo lang($messages, 'navbar', 'admin', 'sub_nav', 'index', 'text'); ?></span>
						  </a>
						</li>
						<li class="nav-item <?php echo ($page[0] == 'admin' AND $page[1] == 'request' OR $codeAuth[0] == 'admin/request') ? 'active' : ''; ?>">
						  <a class="nav-link" href="<?php echo URI; ?>/admin/request">
							<span class="nav-link-icon d-md-none d-lg-inline-block">
								<?php echo lang($messages, 'navbar', 'admin', 'sub_nav', 'request', 'icon'); ?>
							</span>
							<span class="nav-link-title"><?php echo lang($messages, 'navbar', 'admin', 'sub_nav', 'request', 'text'); ?></span>
							<span class="badge badge-sm bg-red"><?php echo $requestTotal; ?></span>
						  </a>
						</li>
						<li class="nav-item <?php echo ($page[0] == 'admin' AND $page[1] == 'wallet' OR $codeAuth[0] == 'admin/wallet') ? 'active' : ''; ?>">
						  <a class="nav-link" href="<?php echo URI; ?>/admin/wallet">
							<span class="nav-link-icon d-md-none d-lg-inline-block">
								<?php echo lang($messages, 'navbar', 'admin', 'sub_nav', 'wallet', 'icon'); ?>
							</span>
							<span class="nav-link-title"><?php echo lang($messages, 'navbar', 'admin', 'sub_nav', 'wallet', 'text'); ?></span>
						  </a>
						</li>
						<li class="nav-item <?php echo ($page[0] == 'admin' AND $page[1] == 'account' OR $codeAuth[0] == 'admin/account') ? 'active' : ''; ?>">
						  <a class="nav-link" href="<?php echo URI; ?>/admin/account">
							<span class="nav-link-icon d-md-none d-lg-inline-block">
								<?php echo lang($messages, 'navbar', 'admin', 'sub_nav', 'account', 'icon'); ?>
							</span>
							<span class="nav-link-title"><?php echo lang($messages, 'navbar', 'admin', 'sub_nav', 'account', 'text'); ?></span>
						  </a>
						</li>
						
					  </ul>
					</div>
					<div class="col-2 d-none d-xxl-block"></div>
				  </div>
				<?php
				}
				?>
				
				
				</div>
			  </div>
			</div>
		</header>
		<?php }
		}		?>
		<div class="page-wrapper">
			<div id="content">
			<?php
			
			if (file_exists($archivo)) {
				include($archivo);
			} else if (file_exists(__DIR__ . '/views/' . $page[0] . '.php')) {
				include(__DIR__ . '/views/' . $page[0] . '.php');
			} else if (file_exists(__DIR__ . '/views/' . $codeAuth[0] . '.php')) {
				include(__DIR__ . '/views/' . $codeAuth[0] . '.php');
			} else {
				if ($codeAuth[0] == '') {
					include(__DIR__ . '/views/index.php');
				}
				echo $page_not_found;
			}

			?>
			</div>
			<?php if ($page[0] != '' AND $page[0] != 'pending' AND $page[0] != 'suspended') { ?>
			<footer class="footer footer-transparent d-print-none">
			  <div class="container-xl">
				<div class="row text-center align-items-center flex-row-reverse">
				  <div class="col-lg-auto ms-lg-auto">
					<ul class="list-inline list-inline-dots mb-0">
					  <li class="list-inline-item"><a href="https://devbybit.com/discord" target="_blank" class="link-secondary" rel="noopener">
						<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-brand-discord"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 12a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" /><path d="M14 12a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" /><path d="M15.5 17c0 1 1.5 3 2 3c1.5 0 2.833 -1.667 3.5 -3c.667 -1.667 .5 -5.833 -1.5 -11.5c-1.457 -1.015 -3 -1.34 -4.5 -1.5l-.972 1.923a11.913 11.913 0 0 0 -4.053 0l-.975 -1.923c-1.5 .16 -3.043 .485 -4.5 1.5c-2 5.667 -2.167 9.833 -1.5 11.5c.667 1.333 2 3 3.5 3c.5 0 2 -2 2 -3" /><path d="M7 16.5c3.5 1 6.5 1 10 0" /></svg>
					  </a></li>
					  <li class="list-inline-item">
							<a href="https://tabler.io" target="_blank" class="link-secondary" rel="noopener">
								<svg xmlns="http://www.w3.org/2000/svg" class="icon text-pink icon-filled icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572"></path></svg>
								Tabler IO
							</a>
						</li>
					</ul>
				  </div>
				  <div class="col-12 col-lg-auto mt-3 mt-lg-0">
					<ul class="list-inline list-inline-dots mb-0">
					  <li class="list-inline-item">
						Copyright &copy; 2024
						<a href="https://devbybit.com/" target="_BLANK" class="link-secondary">DevByBit</a>.
						All rights reserved.
					  </li>
					  <li class="list-inline-item">Mia Account</li>
					</ul>
				  </div>
				</div>
			  </div>
			</footer>
			<?php } ?>
		</div>
    </div>
    <div class="modal modal-blur fade" id="create-wallet" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
			<form method="POST" id="createWallet" class="modal-content">
			  <div class="modal-header">
				<h5 class="modal-title"><?php echo lang($messages, 'wallet', 'modal', 'title'); ?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			  </div>
			  <div class="modal-body">
				<div class="mb-3">
				  <label class="form-label"><?php echo lang($messages, 'wallet', 'modal', 'box', 'label'); ?></label>
				  <input type="text" class="form-control" name="newWalletName" id="newWalletName" placeholder="<?php echo lang($messages, 'wallet', 'modal', 'box', 'placeholder'); ?>">
				</div>
			  </div>
			  <div class="modal-footer">
				<a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
				  <?php echo lang($messages, 'wallet', 'modal', 'buttons', 'cancel'); ?>
				</a>
				<button type="submit" class="btn btn-primary ms-auto" data-bs-dismiss="modal">
				  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
				  <?php echo lang($messages, 'wallet', 'modal', 'buttons', 'submit'); ?>
				</button>
			  </div>
			</form>
      </div>
    </div>
    <div class="modal modal-blur fade" id="select-lang" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" id="modal_product_content">
			<div class="card">
                      <div class="card-header">
                        <h3 class="card-title"><?php echo lang($messages, 'dropdown', 'select_lang'); ?></h3>
                      </div>
                      <div class="list-group list-group-flush">
						
						<?php
						foreach (langList() as $list_lang) {
							$select = ($list_lang['data'] == $_SESSION['lang']) ? 'active' : '';
							echo '<a href="#" class="languages list-group-item list-group-item-action ' . $select . '" data-id="' . $list_lang['data'] . '"><span class="avatar avatar-xs rounded me-2" style="background-image: url(https://flagcdn.com/w160/' . $list_lang['flag'] . '.png)"></span> ' . $list_lang['name'] . '</a>';
						}
						?>
                      </div>
            </div>
			
        </div>
      </div>
    </div>
    <script src="https://devbybit.com/demos/tablerio/dist/libs/fslightbox/index.js?1684106062" defer></script>
    <script src="https://devbybit.com/demos/tablerio/dist/libs/apexcharts/dist/apexcharts.min.js?1684106062" defer></script>
    <script src="https://devbybit.com/demos/tablerio/dist/libs/jsvectormap/dist/js/jsvectormap.min.js?1684106062" defer></script>
    <script src="https://devbybit.com/demos/tablerio/dist/libs/jsvectormap/dist/maps/world.js?1684106062" defer></script>
    <script src="https://devbybit.com/demos/tablerio/dist/libs/jsvectormap/dist/maps/world-merc.js?1684106062" defer></script>
    <script src="https://devbybit.com/demos/tablerio/dist/js/tabler.min.js?1684106062" defer></script>
    <script src="https://devbybit.com/demos/tablerio/dist/js/demo.min.js?1684106062" defer></script>
  </body>
</html>

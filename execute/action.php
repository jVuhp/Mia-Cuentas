<?php
session_start();
require_once('../config.php');
require_once('../function.php');
$request = $_POST['result'];

$chars_wallet = 'ASDFGHJKLPOIUYTREWQZXCVBNM0987654321asdfghjklpoiuytrewqzxcvbnm0123456789';

// =========================================== //
//
//             WALLETS ACTIONS
//
// =========================================== //

if ($request == 'createWallet') {
	
	$name = $_POST['newWalletName'];
	$uuid = randomCodes(6, $chars_wallet) . '-' . randomCodes(10, $chars_wallet) . '-' . randomCodes(18, $chars_wallet) . '-' . randomCodes(11, $chars_wallet);
	
	
	if (!isset($_SESSION['mcs_user'])) {
		echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'inauth')));
		return;
	}
	
	if (empty($name)) {
		echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'wallet', 'create', 'empty')));
		return;
	}
	
	$userSQLs = $connx->prepare("SELECT * FROM `mcs_user` WHERE `id` = ?;");
	$userSQLs->execute([$_SESSION['mcs_user']['id']]);
	$user = $userSQLs->fetch(PDO::FETCH_ASSOC);
	
	$options_user = explode(', ', $user['options']);
	
	$walletCountSQL = $connx->prepare("SELECT COUNT(id) as total FROM `mcs_wallet_user` WHERE `user` = ?;");
	$walletCountSQL->execute([$_SESSION['mcs_user']['id']]);
	$walletCount = $walletCountSQL->fetch(PDO::FETCH_ASSOC);
	$wallet_quant = $walletCount['total'];
	
	if ($options_user[0] <= $wallet_quant) {
		echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'wallet', 'create', 'limit')));
		return;
	}
	
	$walletSQL = $connx->prepare("INSERT INTO `mcs_wallet`(`uuid`, `name`) VALUES (?, ?);");
	$walletSQL->execute([$uuid, $name]);
	$walletID = $connx->lastInsertId();
	
	$userWalletSQL = $connx->prepare("INSERT INTO `mcs_wallet_user`(`wallet`, `user`) VALUES (?, ?);");
	$userWalletSQL->execute([$walletID, $_SESSION['mcs_user']['id']]);
	
	echo json_encode(array('success' => 1, 'message' => lang($messages, 'alert', 'wallet', 'create', 'success')));
	return;

}

if ($request == 'loginWallet') {
	
	$dataid = $_POST['dataid'];
	
	if (!isset($_SESSION['mcs_user'])) {
		echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'inauth')));
		return;
	}
	
	$walletSQL = $connx->prepare("SELECT * FROM `mcs_wallet` WHERE `id` = ?;");
	$walletSQL->execute([$dataid]);
	if ($walletSQL->RowCount() > 0) {
		$wallet = $walletSQL->fetch(PDO::FETCH_ASSOC);
		
		if (!$wallet['status']) {
			echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'wallet', 'login', 'status')));
			return;
		}
		
		$_SESSION['mcs_wallet'] = [
			'id' => $wallet['id'],
			'name' => $wallet['name'],
			'options' => $wallet['options'],
			'status' => $wallet['status'],
		];
		
		echo json_encode(array('success' => 1, 'message' => lang($messages, 'alert', 'wallet', 'login', 'success')));
		return;
	} else {
		echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'wallet', 'login', 'unknown')));
		return;
	}
}

if ($request == 'deleteWallet') {
	
	$dataid = $_POST['dataid'];
	
	if (!isset($_SESSION['mcs_user'])) {
		echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'inauth')));
		return;
	}
	
	$walletSQL = $connx->prepare("SELECT * FROM `mcs_wallet` WHERE `id` = ?;");
	$walletSQL->execute([$dataid]);
	if ($walletSQL->RowCount() > 0) {
		$wallet = $walletSQL->fetch(PDO::FETCH_ASSOC);
		
		$deleteSQL = $connx->prepare("DELETE FROM `mcs_wallet` WHERE `mcs_wallet`.`id` = ?;");
		$deleteSQL->execute([$dataid]);
		
		$removeSQL = $connx->prepare("DELETE FROM `mcs_wallet_user` WHERE `mcs_wallet_user`.`wallet` = ?;");
		$removeSQL->execute([$dataid]);
		
		$dArticlesSQL = $connx->prepare("DELETE FROM `mcs_articulos` WHERE `mcs_articulos`.`wallet` = ?;");
		$dArticlesSQL->execute([$dataid]);
		
		$dAccountSQL = $connx->prepare("DELETE FROM `mcs_cuentas` WHERE `mcs_cuentas`.`wallet` = ?;");
		$dAccountSQL->execute([$dataid]);
		
		$dReceiptSQL = $connx->prepare("DELETE FROM `mcs_pagos` WHERE `mcs_pagos`.`wallet` = ?;");
		$dReceiptSQL->execute([$dataid]);
		
		echo json_encode(array('success' => 1, 'message' => lang($messages, 'alert', 'wallet', 'delete', 'success')));
		return;
	} else {
		echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'wallet', 'delete', 'unknown')));
		return;
	}
}

if ($request == 'verifyWallet') {
	
	$dataid = $_POST['wallet_id'];
	$wallet_bal = ($_POST['wallet_bal'] <= 0) ? '-1' : $_POST['wallet_bal'];
	$wallet_acc = ($_POST['wallet_acc'] <= 0) ? '-1' : $_POST['wallet_acc'];
	$wallet_rec = ($_POST['wallet_rec'] <= 0) ? '-1' : $_POST['wallet_rec'];
	
	$options = $wallet_acc . ', ' . $wallet_bal . ', ' . $wallet_rec;
	
	if (!isset($_SESSION['mcs_user'])) {
		echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'inauth')));
		return;
	}
	
	$walletSQL = $connx->prepare("SELECT * FROM `mcs_wallet` WHERE `id` = ?;");
	$walletSQL->execute([$dataid]);
	if ($walletSQL->RowCount() > 0) {
		$wallet = $walletSQL->fetch(PDO::FETCH_ASSOC);
		
		$updateSQL = $connx->prepare("UPDATE `mcs_wallet` SET `options` = ?, `status` = '1' WHERE `mcs_wallet`.`id` = ?;");
		$updateSQL->execute([$options, $dataid]);
		
		echo json_encode(array('success' => 1, 'message' => lang($messages, 'alert', 'wallet', 'verify', 'success')));
		return;
	} else {
		echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'wallet', 'verify', 'unknown')));
		return;
	}
}

if ($request == 'editWallet') {
	
	$dataid = $_POST['wallet_id'];
	$wallet_name = $_POST['wallet_name'];
	$wallet_bal = ($_POST['wallet_bal'] <= 0) ? '-1' : $_POST['wallet_bal'];
	$wallet_acc = ($_POST['wallet_acc'] <= 0) ? '-1' : $_POST['wallet_acc'];
	$wallet_rec = ($_POST['wallet_rec'] <= 0) ? '-1' : $_POST['wallet_rec'];
	
	$options = $wallet_acc . ', ' . $wallet_bal . ', ' . $wallet_rec;
	
	if (!isset($_SESSION['mcs_user'])) {
		echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'inauth')));
		return;
	}
	
	$walletSQL = $connx->prepare("SELECT * FROM `mcs_wallet` WHERE `id` = ?;");
	$walletSQL->execute([$dataid]);
	if ($walletSQL->RowCount() > 0) {
		$wallet = $walletSQL->fetch(PDO::FETCH_ASSOC);
		
		$updateSQL = $connx->prepare("UPDATE `mcs_wallet` SET `name` = ?,`options` = ? WHERE `mcs_wallet`.`id` = ?;");
		$updateSQL->execute([$wallet_name, $options, $dataid]);
		
		echo json_encode(array('success' => 1, 'message' => lang($messages, 'alert', 'wallet', 'edit', 'success')));
		return;
	} else {
		echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'wallet', 'edit', 'unknown')));
		return;
	}
}

if ($request == 'deSuspend_wallet') {
	
	$dataid = $_POST['dataid'];
	
	if (!isset($_SESSION['mcs_user'])) {
		echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'inauth')));
		return;
	}
	
	$adminSQL = $connx->prepare("SELECT * FROM `mcs_admin` WHERE `gmail` = ?;");
	$adminSQL->execute([$_SESSION['mcs_user']['email']]);
	if ($adminSQL->RowCount() > 0) {
		$walletSQL = $connx->prepare("SELECT * FROM `mcs_wallet` WHERE `id` = ?;");
		$walletSQL->execute([$dataid]);
		if ($walletSQL->RowCount() > 0) {
			$wallet = $walletSQL->fetch(PDO::FETCH_ASSOC);
			
			$updateSQL = $connx->prepare("UPDATE `mcs_wallet` SET `status` = '1' WHERE `mcs_wallet`.`id` = ?;");
			$updateSQL->execute([$wallet['id']]);
			
			echo json_encode(array('success' => 1, 'message' => lang($messages, 'alert', 'wallet', 'unsuspend', 'success')));
			return;
		} else {
			echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'wallet', 'unsuspend', 'unknown')));
			return;
		}
	}
}

if ($request == 'Suspend_wallet') {
	
	$dataid = $_POST['dataid'];
	
	if (!isset($_SESSION['mcs_user'])) {
		echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'inauth')));
		return;
	}
	
	$adminSQL = $connx->prepare("SELECT * FROM `mcs_admin` WHERE `gmail` = ?;");
	$adminSQL->execute([$_SESSION['mcs_user']['email']]);
	if ($adminSQL->RowCount() > 0) {
		$walletSQL = $connx->prepare("SELECT * FROM `mcs_wallet` WHERE `id` = ?;");
		$walletSQL->execute([$dataid]);
		if ($walletSQL->RowCount() > 0) {
			$wallet = $walletSQL->fetch(PDO::FETCH_ASSOC);
			
			$updateSQL = $connx->prepare("UPDATE `mcs_wallet` SET `status` = '2' WHERE `mcs_wallet`.`id` = ?;");
			$updateSQL->execute([$wallet['id']]);
			
			echo json_encode(array('success' => 1, 'message' => lang($messages, 'alert', 'wallet', 'suspend', 'success')));
			return;
		} else {
			echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'wallet', 'suspend', 'unknown')));
			return;
		}
	}
}

// =========================================== //
//
//             USERS ACTIONS
//
// =========================================== //



if ($request == 'deleteUser') {
	
	$dataid = $_POST['dataid'];
	
	if (!isset($_SESSION['mcs_user'])) {
		echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'inauth')));
		return;
	}
	
	$adminSQL = $connx->prepare("SELECT * FROM `mcs_admin` WHERE `gmail` = ?;");
	$adminSQL->execute([$_SESSION['mcs_user']['email']]);
	if ($adminSQL->RowCount() > 0) {
		$walletSQL = $connx->prepare("SELECT * FROM `mcs_user` WHERE `id` = ?;");
		$walletSQL->execute([$dataid]);
		if ($walletSQL->RowCount() > 0) {
			$wallet = $walletSQL->fetch(PDO::FETCH_ASSOC);
			
			$deleteSQL = $connx->prepare("DELETE FROM `mcs_user` WHERE `mcs_user`.`id` = ?;");
			$deleteSQL->execute([$dataid]);
			
			echo json_encode(array('success' => 1, 'message' => lang($messages, 'alert', 'user', 'delete', 'success')));
			return;
		} else {
			echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'user', 'delete', 'unknown')));
			return;
		}
	}
}

if ($request == 'verifyUser') {
	
	$dataid = $_POST['wallet_id'];
	
	if (!isset($_SESSION['mcs_user'])) {
		echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'inauth')));
		return;
	}
	$wallet_bal = ($_POST['wallet_bal'] <= 0) ? '-1' : $_POST['wallet_bal'];
	
	$adminSQL = $connx->prepare("SELECT * FROM `mcs_admin` WHERE `gmail` = ?;");
	$adminSQL->execute([$_SESSION['mcs_user']['email']]);
	if ($adminSQL->RowCount() > 0) {
		$walletSQL = $connx->prepare("SELECT * FROM `mcs_user` WHERE `id` = ?;");
		$walletSQL->execute([$dataid]);
		if ($walletSQL->RowCount() > 0) {
			$wallet = $walletSQL->fetch(PDO::FETCH_ASSOC);
			
			$updateSQL = $connx->prepare("UPDATE `mcs_user` SET `status` = '1', `options` = ? WHERE `mcs_user`.`id` = ?;");
			$updateSQL->execute([$wallet_bal, $dataid]);
			
			echo json_encode(array('success' => 1, 'message' => lang($messages, 'alert', 'user', 'verify', 'success')));
			return;
		} else {
			echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'user', 'verify', 'unknown')));
			return;
		}
	}
}

if ($request == 'editUser') {
	
	$dataid = $_POST['wallet_id'];
	$wallet_name = $_POST['wallet_name'];
	
	if (!isset($_SESSION['mcs_user'])) {
		echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'inauth')));
		return;
	}
	$wallet_bal = ($_POST['wallet_bal'] <= 0) ? '-1' : $_POST['wallet_bal'];
	
	$adminSQL = $connx->prepare("SELECT * FROM `mcs_admin` WHERE `gmail` = ?;");
	$adminSQL->execute([$_SESSION['mcs_user']['email']]);
	if ($adminSQL->RowCount() > 0) {
		$walletSQL = $connx->prepare("SELECT * FROM `mcs_user` WHERE `id` = ?;");
		$walletSQL->execute([$dataid]);
		if ($walletSQL->RowCount() > 0) {
			$wallet = $walletSQL->fetch(PDO::FETCH_ASSOC);
			
			$updateSQL = $connx->prepare("UPDATE `mcs_user` SET `name` = ?, `options` = ? WHERE `mcs_user`.`id` = ?;");
			$updateSQL->execute([$wallet_name, $wallet_bal, $dataid]);
			
			echo json_encode(array('success' => 1, 'message' => lang($messages, 'alert', 'user', 'edit', 'success')));
			return;
		} else {
			echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'user', 'edit', 'unknown')));
			return;
		}
	}
}

if ($request == 'removeAdmin') {
	
	$dataid = $_POST['dataid'];
	
	if (!isset($_SESSION['mcs_user'])) {
		echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'inauth')));
		return;
	}
	
	$adminSQL = $connx->prepare("SELECT * FROM `mcs_admin` WHERE `gmail` = ?;");
	$adminSQL->execute([$_SESSION['mcs_user']['email']]);
	if ($adminSQL->RowCount() > 0) {
		$walletSQL = $connx->prepare("SELECT * FROM `mcs_admin` WHERE `g_id` = ?;");
		$walletSQL->execute([$dataid]);
		if ($walletSQL->RowCount() > 0) {
			$wallet = $walletSQL->fetch(PDO::FETCH_ASSOC);
			
			$updateSQL = $connx->prepare("DELETE FROM `mcs_admin` WHERE `mcs_admin`.`g_id` = ?;");
			$updateSQL->execute([$dataid]);
			
			echo json_encode(array('success' => 1, 'message' => lang($messages, 'alert', 'user', 'remove_admin', 'success')));
			return;
		} else {
			echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'user', 'remove_admin', 'unknown')));
			return;
		}
	}
}

if ($request == 'addAdmin') {
	
	$dataid = $_POST['dataid'];
	
	if (!isset($_SESSION['mcs_user'])) {
		echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'inauth')));
		return;
	}
	
	$adminSQL = $connx->prepare("SELECT * FROM `mcs_admin` WHERE `gmail` = ?;");
	$adminSQL->execute([$_SESSION['mcs_user']['email']]);
	if ($adminSQL->RowCount() > 0) {
		$walletSQL = $connx->prepare("SELECT * FROM `mcs_user` WHERE `g_id` = ?;");
		$walletSQL->execute([$dataid]);
		if ($walletSQL->RowCount() > 0) {
			$wallet = $walletSQL->fetch(PDO::FETCH_ASSOC);
			
			$updateSQL = $connx->prepare("INSERT INTO `mcs_admin`(`g_id`, `gmail`, `status`) VALUES (?, ?, '1');");
			$updateSQL->execute([$wallet['g_id'], $wallet['email']]);
			
			echo json_encode(array('success' => 1, 'message' => lang($messages, 'alert', 'user', 'add_admin', 'success')));
			return;
		} else {
			echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'user', 'add_admin', 'unknown')));
			return;
		}
	}
}

if ($request == 'deSuspend') {
	
	$dataid = $_POST['dataid'];
	
	if (!isset($_SESSION['mcs_user'])) {
		echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'inauth')));
		return;
	}
	
	$adminSQL = $connx->prepare("SELECT * FROM `mcs_admin` WHERE `gmail` = ?;");
	$adminSQL->execute([$_SESSION['mcs_user']['email']]);
	if ($adminSQL->RowCount() > 0) {
		$walletSQL = $connx->prepare("SELECT * FROM `mcs_user` WHERE `id` = ?;");
		$walletSQL->execute([$dataid]);
		if ($walletSQL->RowCount() > 0) {
			$wallet = $walletSQL->fetch(PDO::FETCH_ASSOC);
			
			$updateSQL = $connx->prepare("UPDATE `mcs_user` SET `status` = '1' WHERE `mcs_user`.`id` = ?;");
			$updateSQL->execute([$wallet['id']]);
			
			echo json_encode(array('success' => 1, 'message' => lang($messages, 'alert', 'user', 'unsuspend', 'success')));
			return;
		} else {
			echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'user', 'unsuspend', 'unknown')));
			return;
		}
	}
}

if ($request == 'Suspend') {
	
	$dataid = $_POST['dataid'];
	
	if (!isset($_SESSION['mcs_user'])) {
		echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'inauth')));
		return;
	}
	
	$adminSQL = $connx->prepare("SELECT * FROM `mcs_admin` WHERE `gmail` = ?;");
	$adminSQL->execute([$_SESSION['mcs_user']['email']]);
	if ($adminSQL->RowCount() > 0) {
		$walletSQL = $connx->prepare("SELECT * FROM `mcs_user` WHERE `id` = ?;");
		$walletSQL->execute([$dataid]);
		if ($walletSQL->RowCount() > 0) {
			$wallet = $walletSQL->fetch(PDO::FETCH_ASSOC);
			
			$updateSQL = $connx->prepare("UPDATE `mcs_user` SET `status` = '2' WHERE `mcs_user`.`id` = ?;");
			$updateSQL->execute([$wallet['id']]);
			
			echo json_encode(array('success' => 1, 'message' => lang($messages, 'alert', 'user', 'suspend', 'success')));
			return;
		} else {
			echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'user', 'suspend', 'unknown')));
			return;
		}
	}
}

// =========================================== //
//
//             INDEX ACTIONS
//
// =========================================== //


if ($request == 'logout') {
	setcookie('mcs_logged', 0, time() + 1, '/');
	setcookie('mcs_user', 0, time() + 1, '/');
	session_destroy();
}

if ($request == 'change_lang') {
	$dataid = $_POST['data_id']; 

	$_SESSION['lang'] = $dataid;
	setcookie('lang', $dataid, time() + (86400 * 30), '/');
	echo json_encode(array('type' => 'success'));
	return;
}

if ($request == 'inscribir') {
	
	$nombre = $_POST['nombre'];
	
	if (!isset($_SESSION['mcs_user'])) {
		echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'inauth')));
		return;
	}
	
	$walletSQL = $connx->prepare("SELECT * FROM `mcs_wallet` WHERE `id` = ?;");
	$walletSQL->execute([$_SESSION['mcs_wallet']['id']]);
	$wallet = $walletSQL->fetch(PDO::FETCH_ASSOC);
	
	$walletOptions = explode(', ', $wallet['options']);
	
	$walletOtherSQL = $connx->prepare("SELECT COUNT(id) AS total FROM `mcs_cuentas` WHERE `wallet` = ?;");
	$walletOtherSQL->execute([$_SESSION['mcs_wallet']['id']]);
	$walletOther = $walletOtherSQL->fetch(PDO::FETCH_ASSOC);
	
	if ($walletOptions[0] != '-1') {
		if ($walletOptions[0] <= $walletOther['total']) {
			echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'limited')));
			return;
		}
	}
	
	if (empty($nombre)) {
		echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'auth', 'register', 'empty')));
		return;
	}
	
	if (!isset($_SESSION['mcs_wallet'])) {
		echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'auth', 'register', 'unknown')));
		return;
	}
	
	$verifyUsername = $connx->prepare("SELECT * FROM `mcs_cuentas` WHERE `wallet` = ? AND `nombre` = ?;");
	$verifyUsername->execute([$_SESSION['mcs_wallet']['id'], $nombre]);
	if ($verifyUsername->RowCount() > 0) {
		echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'auth', 'register', 'name_used')));
		return;
	}
	
	$insertSQL = $connx->prepare("INSERT INTO `mcs_cuentas` (`id`, `wallet`, `nombre`, `since`) VALUES (NULL, ?, ?, ?);");
	$insertSQL->execute([$_SESSION['mcs_wallet']['id'], $nombre, $tiempo]);
	
	echo json_encode(array('success' => 1, 'message' => lang($messages, 'alert', 'auth', 'register', 'success'), 'id' => $connx->lastInsertId()));
	
}

if ($request == 'articulo_new') {
	
	$cuenta = $_POST['cuenta'];
	$articulo = $_POST['articulo'];
	$costo = $_POST['costo'];
	
	if (!isset($_SESSION['mcs_user'])) {
		echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'inauth')));
		return;
	}
	
	$walletSQL = $connx->prepare("SELECT * FROM `mcs_wallet` WHERE `id` = ?;");
	$walletSQL->execute([$_SESSION['mcs_wallet']['id']]);
	$wallet = $walletSQL->fetch(PDO::FETCH_ASSOC);
	
	$walletOptions = explode(', ', $wallet['options']);
	
	$walletOtherSQL = $connx->prepare("SELECT SUM(total) AS total FROM `mcs_articulos` WHERE `wallet` = ?;");
	$walletOtherSQL->execute([$_SESSION['mcs_wallet']['id']]);
	$walletOther = $walletOtherSQL->fetch(PDO::FETCH_ASSOC);
	
	$total_limit = $walletOther['total'] + $costo;
	
	if ($walletOptions[1] != '-1') {
		if ($walletOptions[1] <= $total_limit) {
			echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'limited')));
			return;
		}
	}
	
	if (empty($articulo) OR empty($costo)) {
		echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'articles', 'create', 'empty')));
		return;
	}
	
	if (!isset($_SESSION['mcs_wallet'])) {
		echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'articles', 'create', 'unknown')));
		return;
	}
	
	$insertSQL = $connx->prepare("INSERT INTO `mcs_articulos`(`wallet`, `cuenta`, `articulo`, `total`, `since`) VALUES (?, ?, ?, ?, ?);");
	$insertSQL->execute([$_SESSION['mcs_wallet']['id'], $cuenta, $articulo, $costo, $tiempo]);
	
	echo json_encode(array('success' => 1, 'message' => lang($messages, 'alert', 'articles', 'create', 'success'), 'id' => $connx->lastInsertId()));
	
}


if ($request == 'recibo') {
	
	$cuenta = $_POST['cuenta'];
	$costo = $_POST['costo'];
	$descripcion = (!empty($_POST['descripcion'])) ? $_POST['descripcion'] : NULL;
	
	
	if (!isset($_SESSION['mcs_user'])) {
		echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'inauth')));
		return;
	}
	
	$walletSQL = $connx->prepare("SELECT * FROM `mcs_wallet` WHERE `id` = ?;");
	$walletSQL->execute([$_SESSION['mcs_wallet']['id']]);
	$wallet = $walletSQL->fetch(PDO::FETCH_ASSOC);
	
	$walletOptions = explode(', ', $wallet['options']);
	
	$walletOtherSQL = $connx->prepare("SELECT COUNT(id) AS total FROM `mcs_pagos` WHERE `wallet` = ?;");
	$walletOtherSQL->execute([$_SESSION['mcs_wallet']['id']]);
	$walletOther = $walletOtherSQL->fetch(PDO::FETCH_ASSOC);
	
	if ($walletOptions[2] != '-1') {
		if ($walletOptions[2] <= $walletOther['total']) {
			echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'limited')));
			return;
		}
	}
	
	if (empty($cuenta) OR empty($costo)) {
		echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'receipt', 'create', 'empty')));
		return;
	}
	
	if (!isset($_SESSION['mcs_wallet'])) {
		echo json_encode(array('success' => 2, 'message' => lang($messages, 'alert', 'receipt', 'create', 'unknown')));
		return;
	}
	
	$insertSQL = $connx->prepare("INSERT INTO `mcs_pagos`(`wallet`, `cuenta`, `total`, `descripcion`, `since`) VALUES (?, ?, ?, ?, ?);");
	$insertSQL->execute([$_SESSION['mcs_wallet']['id'], $cuenta, $costo, $descripcion, $tiempo]);
	
	echo json_encode(array('success' => 1, 'message' => lang($messages, 'alert', 'receipt', 'create', 'success'), 'id' => $connx->lastInsertId()));
	
}


if ($_POST['result'] == 'sub_search_client') {
	try {
		$userSQL = $connx->prepare("SELECT * FROM `mcs_articulos` WHERE `wallet` = ?;");
		$userSQL->execute([$_SESSION['mcs_wallet']['id']]);

		$userData = [];

		while ($user = $userSQL->fetch(PDO::FETCH_ASSOC)) {
			$total = $user['total'];
			if (!isset($userData[$total])) {
				$userListData = ['name' => $user['articulo'], 'total' => $total];
				$userData[$total] = $userListData;
			}
		}

		$query = isset($_POST['query']) ? $_POST['query'] : '';

		$filteredResults = array_filter($userData, function($item) use ($query) {
			$nameMatch = stripos($item['name'], $query) !== false;
			$totalMatch = stripos($item['total'], $query) !== false;
			
			return $nameMatch || $totalMatch;
		});

		header('Content-Type: application/json');
		echo json_encode(array_values($filteredResults));
	} catch (Exception $e) {
		header('HTTP/1.1 500 Internal Server Error');
		echo json_encode(array('error' => $e->getMessage()));
	}
}
?>
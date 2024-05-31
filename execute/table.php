<?php
session_start();
if (file_exists('config.php')) {
	require_once('config.php');
} else if (file_exists('../config.php')) {
	require_once('../config.php');
} else {
	require_once('../../config.php');
}

if (file_exists('function.php')) {
	require_once('function.php');
} else if (file_exists('../function.php')) {
	require_once('../function.php');
} else {
	require_once('../../function.php');
}

$request = $_POST['result']; 


if ($request == 'cuentas') {
	
	$search = (!empty($_POST['search'])) ? $_POST['search'] : '';
	$where = (!empty($_POST['where'])) ? $_POST['where'] : 0;
	$pagination = (!empty($_POST['pag'])) ? $_POST['pag'] : 1;
	$total = (!empty($_POST['total'])) ? $_POST['total'] : 100;	
	$wheres = ($where == 1) ? "ORDER BY id " : "ORDER BY id DESC";
	$compag = (int)(!isset($pagination)) ? 1 : $pagination;

	
		
				
					$params = (!empty($search)) ? [$_SESSION['mcs_wallet']['id'], '%' . $search . '%'] : [$_SESSION['mcs_wallet']['id']];
					$searching = (!empty($search)) ? "WHERE `wallet` = ? AND `nombre` LIKE ?" : "WHERE `wallet` = ?";

					$plataformSQL = $connx->prepare("SELECT * FROM `mcs_cuentas` $searching");
					$plataformSQL->execute($params);
					$TotalRegistro = ceil($plataformSQL->RowCount() / $total);
					ob_start();
					$cuentaSQL = $connx->prepare("SELECT * FROM `mcs_cuentas` $searching $wheres LIMIT " . (($compag - 1) * $total) . " , " . $total);
					$cuentaSQL->execute($params);
					if ($cuentaSQL->RowCount() > 0) {
					while ($cuenta = $cuentaSQL->fetch(PDO::FETCH_ASSOC)) {
						
						$saldo = 0;
						$articulosSQL = $connx->prepare("SELECT * FROM `mcs_articulos` WHERE `wallet` = ? AND `cuenta` = ?;");
						$articulosSQL->execute([$_SESSION['mcs_wallet']['id'], $cuenta['id']]);
						while ($articulos = $articulosSQL->fetch(PDO::FETCH_ASSOC)) {
							if ($articulos['estado'] == NULL) {
								$saldo += $articulos['total'];
							}
						}
						$saldoPay = 0;
						$pagosSQL = $connx->prepare("SELECT SUM(total) AS pagado FROM `mcs_pagos` WHERE `wallet` = ? AND `cuenta` = ?;");
						$pagosSQL->execute([$_SESSION['mcs_wallet']['id'], $cuenta['id']]);
						$pagos = $pagosSQL->fetch(PDO::FETCH_ASSOC);
						$saldoPay = $pagos['pagado'];
						
						$saldo_final = $saldo - $saldoPay;
				?>
                  <tr>
                          <td data-label="<?php echo lang($messages, 'cuentas', 'table', 'head', 'account'); ?>"><?php echo $cuenta['nombre']; ?></td>
                          <td data-label="<?php echo lang($messages, 'cuentas', 'table', 'head', 'total_balance'); ?>"><?php echo '<span class="text-danger">$' . number_format($saldo, 2, ',', '.') . '</span>'; ?></td>
                          <td data-label="<?php echo lang($messages, 'cuentas', 'table', 'head', 'paid_balance'); ?>"><?php echo '<span class="text-success">$' . number_format($saldoPay, 2, ',', '.') . '</span>'; ?></td>
                          <td data-label="<?php echo lang($messages, 'cuentas', 'table', 'head', 'debt_balance'); ?>"><?php echo '<span class="text-warning">$' . number_format($saldo_final, 2, ',', '.') . '</span>'; ?></td>
                          <td class="text-secondary" data-label="<?php echo lang($messages, 'cuentas', 'table', 'head', 'registered'); ?>"><?php echo counttime($cuenta['since']); ?></td>
                          <td>
                            <div class="btn-list flex-nowrap">
                              <a href="<?php echo URI; ?>/cuentas/<?php echo $cuenta['id']; ?>" class="btn">
                                <?php echo lang($messages, 'cuentas', 'table', 'actions'); ?>
                              </a>
                            </div>
                          </td>
                        </tr>
				  <?php
						
					}
				
					} else echo '<tr><td colspan="12">' . lang($messages, 'error', 'not_results_found') . '</td></tr>';
					$html = ob_get_clean();
	
	$totalRegistros = $plataformSQL->rowCount();

	$inicio = (($compag - 1) * $total) + 1;
	$fin = min($inicio + $total - 1, $totalRegistros);
	ob_start();
	echo paginationButtons($TotalRegistro, $compag, $total, 'updatePage');
	$paginations_list = ob_get_clean();
	
	$data = [
		'totalRegistros' => $totalRegistros,
		'inicio' => $inicio,
		'fin' => $fin,
		'html' => $html,
		'paginations_list' => $paginations_list,
		'dtype' => $design_type,
	];

	echo json_encode($data);
	
	
}

if ($request == 'articulos') {
	$cuentaid = $_POST['cuentaID']; 
	$search = (!empty($_POST['search'])) ? $_POST['search'] : '';
	$where = (!empty($_POST['where'])) ? $_POST['where'] : 0;
	$pagination = (!empty($_POST['pag'])) ? $_POST['pag'] : 1;
	$total = (!empty($_POST['total'])) ? $_POST['total'] : 100;	
	$wheres = ($where == 1) ? "ORDER BY id " : "ORDER BY id DESC";
	$compag = (int)(!isset($pagination)) ? 1 : $pagination;

	
	$saldoPay = 0;
	$saldoNPay = 0;
	$pagosSQL = $connx->prepare("SELECT SUM(total) AS pagado FROM `mcs_pagos` WHERE `cuenta` = ?;");
	$pagosSQL->execute([$cuentaid]);
	$pagos = $pagosSQL->fetch(PDO::FETCH_ASSOC);
	$saldoPay = $pagos['pagado'];

	$articulosPaySQL = $connx->prepare("SELECT * FROM `mcs_articulos` WHERE `cuenta` = ?;");
	$articulosPaySQL->execute([$cuentaid]);
	while ($articulosPay = $articulosPaySQL->fetch(PDO::FETCH_ASSOC)) {
		if ($articulosPay['estado'] != NULL) {
			$saldoPay += $pagos['pagado'];
		} else {
			$saldoNPay += $articulosPay['total'];
		}
	}
	$saldoNPay = $saldoNPay - $pagos['pagado'];
		
				
					$params = (!empty($search)) ? [$cuentaid, '%' . $search . '%'] : [$cuentaid];
					$searching = (!empty($search)) ? "WHERE `cuenta` = ? AND `articulo` LIKE ?" : "WHERE `cuenta` = ?";

					$plataformSQL = $connx->prepare("SELECT * FROM `mcs_articulos` $searching");
					$plataformSQL->execute($params);
					$TotalRegistro = ceil($plataformSQL->RowCount() / $total);
					ob_start();
					$articulosSQL = $connx->prepare("SELECT * FROM `mcs_articulos` $searching $wheres LIMIT " . (($compag - 1) * $total) . " , " . $total);
					$articulosSQL->execute($params);
					if ($articulosSQL->RowCount() > 0) {
					while ($articulos = $articulosSQL->fetch(PDO::FETCH_ASSOC)) {
						

				?>
                  <tr>
                          <td data-label="<?php echo lang($messages, 'cuentas', 'articles', 'table', 'head', 'article'); ?>"><?php echo $articulos['articulo']; ?></td>
                          <td data-label="<?php echo lang($messages, 'cuentas', 'articles', 'table', 'head', 'balance'); ?>">
						  <?php 
						  if ($articulos['estado'] == NULL) {
							echo '$' . number_format($articulos['total'], 2, ',', '.'); 
						  } else {
							echo '<del>$' . number_format($articulos['total'], 2, ',', '.') . '</del>'; 
						  }
						  ?></td>
                          <td class="text-secondary" data-label="<?php echo lang($messages, 'cuentas', 'articles', 'table', 'head', 'registered'); ?>"><?php echo counttime($articulos['since']); ?></td>
                          <td>
                            <div class="btn-list flex-nowrap">
                              <a href="#" onclick="checkArticulo('<?php echo $articulos['id']; ?>', '<?php echo $articulos['articulo']; ?>', '<?php echo $articulos['total']; ?>', '<?php echo $articulos['since']; ?>');" class="btn"><?php echo lang($messages, 'cuentas', 'articles', 'table', 'actions'); ?></a>
                            </div>
                          </td>
                        </tr>
				  <?php
						
					}
				
					} else echo '<tr><td colspan="12">' . lang($messages, 'error', 'not_results_found') . '</td></tr>';
					$html = ob_get_clean();
	
	$totalRegistros = $plataformSQL->rowCount();

	$inicio = (($compag - 1) * $total) + 1;
	$fin = min($inicio + $total - 1, $totalRegistros);
	ob_start();
	echo paginationButtons($TotalRegistro, $compag, $total, 'updatePage');
	$paginations_list = ob_get_clean();
	
	$data = [
		'totalRegistros' => $totalRegistros,
		'inicio' => $inicio,
		'fin' => $fin,
		'html' => $html,
		'paginations_list' => $paginations_list,
		'dtype' => $design_type,
		'debt_bal' => '$' . number_format($saldoNPay, 2, ',', '.'),
	];

	echo json_encode($data);
	
	
}

if ($request == 'receipt') {
	$cuentaid = $_POST['cuentaID']; 
	$search = (!empty($_POST['search'])) ? $_POST['search'] : '';
	$where = (!empty($_POST['where'])) ? $_POST['where'] : 0;
	$pagination = (!empty($_POST['pag'])) ? $_POST['pag'] : 1;
	$total = (!empty($_POST['total'])) ? $_POST['total'] : 100;	
	$wheres = ($where == 1) ? "ORDER BY id " : "ORDER BY id DESC";
	$compag = (int)(!isset($pagination)) ? 1 : $pagination;

	
	$saldoPay = 0;
	$saldoNPay = 0;
	$pagosSQL = $connx->prepare("SELECT SUM(total) AS pagado FROM `mcs_pagos` WHERE `cuenta` = ?;");
	$pagosSQL->execute([$cuentaid]);
	$pagos = $pagosSQL->fetch(PDO::FETCH_ASSOC);
	$saldoPay = $pagos['pagado'];

	$articulosPaySQL = $connx->prepare("SELECT * FROM `mcs_articulos` WHERE `cuenta` = ?;");
	$articulosPaySQL->execute([$cuentaid]);
	while ($articulosPay = $articulosPaySQL->fetch(PDO::FETCH_ASSOC)) {
		if ($articulosPay['estado'] != NULL) {
			$saldoPay += $pagos['pagado'];
		} else {
			$saldoNPay += $articulosPay['total'];
		}
	}
	$saldoNPay = $saldoNPay - $pagos['pagado'];
		
				
					$params = (!empty($search)) ? [$cuentaid, '%' . $search . '%'] : [$cuentaid];
					$searching = (!empty($search)) ? "WHERE `cuenta` = ? AND `articulo` LIKE ?" : "WHERE `cuenta` = ?";

					$plataformSQL = $connx->prepare("SELECT * FROM `mcs_pagos` $searching");
					$plataformSQL->execute($params);
					$TotalRegistro = ceil($plataformSQL->RowCount() / $total);
					ob_start();
					$articulosSQL = $connx->prepare("SELECT * FROM `mcs_pagos` $searching $wheres LIMIT " . (($compag - 1) * $total) . " , " . $total);
					$articulosSQL->execute($params);
					if ($articulosSQL->RowCount() > 0) {
					while ($articulos = $articulosSQL->fetch(PDO::FETCH_ASSOC)) {
						

				?>
					<tr>
                        <td data-label="<?php echo lang($messages, 'cuentas', 'receipt', 'table', 'head', 'description'); ?>"><?php echo $articulos['descripcion']; ?></td>
                        <td data-label="<?php echo lang($messages, 'cuentas', 'receipt', 'table', 'head', 'total'); ?>"><?php echo '$' . number_format($articulos['total'], 2, ',', '.'); ?></td>
                        <td class="text-secondary" data-label="<?php echo lang($messages, 'cuentas', 'receipt', 'table', 'head', 'registered'); ?>"><?php echo counttime($articulos['since']); ?></td>
                        <td>
                            <div class="btn-list flex-nowrap">
                              <a href="#" class="btn"><?php echo lang($messages, 'cuentas', 'articles', 'table', 'actions'); ?></a>
                            </div>
                        </td>
                    </tr>
				  <?php
						
					}
				
					} else echo '<tr><td colspan="12">' . lang($messages, 'error', 'not_results_found') . '</td></tr>';
					$html = ob_get_clean();
	
	$totalRegistros = $plataformSQL->rowCount();

	$inicio = (($compag - 1) * $total) + 1;
	$fin = min($inicio + $total - 1, $totalRegistros);
	ob_start();
	echo paginationButtons($TotalRegistro, $compag, $total, 'updatePage');
	$paginations_list = ob_get_clean();
	
	$data = [
		'totalRegistros' => $totalRegistros,
		'inicio' => $inicio,
		'fin' => $fin,
		'html' => $html,
		'paginations_list' => $paginations_list,
		'dtype' => $design_type,
		'debt_bal' => '$' . number_format($saldoNPay, 2, ',', '.'),
	];

	echo json_encode($data);
}

// ADMIN
// ADMIN
// ADMIN
// ADMIN
// ADMIN

if ($request == 'admin_account') {
	$cuentaid = $_POST['cuentaID']; 
	$search = (!empty($_POST['search'])) ? $_POST['search'] : '';
	$where = (!empty($_POST['where'])) ? $_POST['where'] : 0;
	$pagination = (!empty($_POST['pag'])) ? $_POST['pag'] : 1;
	$total = (!empty($_POST['total'])) ? $_POST['total'] : 100;	
	$wheres = ($where == 1) ? "ORDER BY id " : "ORDER BY id DESC";
	$compag = (int)(!isset($pagination)) ? 1 : $pagination;
		
	$params = (!empty($search)) ? ['%' . $search . '%', '%' . $search . '%', '%' . $search . '%'] : [];
	$searching = (!empty($search)) ? "WHERE `g_id` = ? OR `name` LIKE ? OR `email` LIKE ?" : " ";

	$plataformSQL = $connx->prepare("SELECT * FROM `mcs_user` $searching");
	$plataformSQL->execute($params);
	$TotalRegistro = ceil($plataformSQL->RowCount() / $total);
	
	ob_start();
	$docsSQL = $connx->prepare("SELECT * FROM `mcs_user` $searching $wheres LIMIT " . (($compag - 1) * $total) . " , " . $total);
	$docsSQL->execute($params);
	if ($docsSQL->RowCount() > 0) {
		while ($docs = $docsSQL->fetch(PDO::FETCH_ASSOC)) {
			
		$adminSQL = $connx->prepare("SELECT * FROM `mcs_admin` WHERE `g_id` = ?;");
		$adminSQL->execute([$docs['g_id']]);
		$admin = $adminSQL->fetch(PDO::FETCH_ASSOC);
	?>
		<tr>
            <td data-label="<?php echo lang($messages, 'admin', 'account', 'table', 'user'); ?>">
				<div class="d-flex py-1 align-items-center">
                    <span class="avatar me-2" style="background-image: url(<?php echo $docs['avatar']; ?>)"></span>
                    <div class="flex-fill">
                        <div class="font-weight-medium"><?php echo $docs['name']; ?></div>
                        <div class="text-secondary"><a href="#" class="text-reset"><?php echo ($adminSQL->RowCount() > 0) ? 'Admin' : 'Member'; ?></a></div>
                    </div>
                </div>
			</td>
            <td data-label="<?php echo lang($messages, 'admin', 'account', 'table', 'gmail'); ?>"><?php echo $docs['email']; ?></td>
            <td data-label="<?php echo lang($messages, 'admin', 'account', 'table', 'id'); ?>"><?php echo $docs['g_id']; ?></td>
            <td data-label="<?php echo lang($messages, 'admin', 'account', 'table', 'status', 'title'); ?>">
				<?php echo ($docs['status'] == 2) ? '<b class="text-danger">' . lang($messages, 'admin', 'account', 'table', 'status', 'suspend') . '</b>' :
				(($docs['status']) ? '<b class="text-success">' . lang($messages, 'admin', 'account', 'table', 'status', 'active') . '</b>' : '<b class="text-warning">' . lang($messages, 'admin', 'account', 'table', 'status', 'verify') . '</b>'); ?>
			</td>
            <td class="text-secondary" data-label="<?php echo lang($messages, 'admin', 'account', 'table', 'registered'); ?>"><?php echo counttime($docs['since']); ?></td>
            <td>
				<?php if ($_SESSION['mcs_user']['id'] != $docs['id']) { ?>
                <div class="btn-list flex-nowrap">
					<span class="dropdown">
                        <button class="btn dropdown-toggle align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown" aria-expanded="true">
							<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12h2" /><path d="M17 12h2" /><path d="M11 12h2" /></svg>
						</button>
                        <div class="dropdown-menu dropdown-menu-end" data-popper-placement="top-end" data-popper-reference-hidden="" data-popper-escaped="" style="position: absolute; inset: auto 0px 0px auto; margin: 0px; transform: translate(0px, -2px);">
							
                            <a class="dropdown-item" href="#" onclick="checkUser('<?php echo $docs['id']; ?>', '<?php echo $docs['name']; ?>', '<?php echo $docs['avatar']; ?>');">
								<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
                                <?php echo lang($messages, 'admin', 'account', 'table', 'buttons', 'edit'); ?>
                            </a>
							
							<?php if ($docs['status'] != 2) { ?>
                            <a class="dropdown-item" href="#" onclick="suspend('<?php echo $docs['id']; ?>');">
								<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M5.7 5.7l12.6 12.6" /></svg>
                                <?php echo lang($messages, 'admin', 'account', 'table', 'buttons', 'suspend'); ?>
                            </a>
							<?php if ($docs['status']) { ?>
							<?php if ($adminSQL->RowCount() == 0) { ?>
							
                            <a class="dropdown-item" href="#" onclick="addAdmin('<?php echo $docs['g_id']; ?>');">
								<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17.8 19.817l-2.172 1.138a.392 .392 0 0 1 -.568 -.41l.415 -2.411l-1.757 -1.707a.389 .389 0 0 1 .217 -.665l2.428 -.352l1.086 -2.193a.392 .392 0 0 1 .702 0l1.086 2.193l2.428 .352a.39 .39 0 0 1 .217 .665l-1.757 1.707l.414 2.41a.39 .39 0 0 1 -.567 .411l-2.172 -1.138z" /><path d="M6.2 19.817l-2.172 1.138a.392 .392 0 0 1 -.568 -.41l.415 -2.411l-1.757 -1.707a.389 .389 0 0 1 .217 -.665l2.428 -.352l1.086 -2.193a.392 .392 0 0 1 .702 0l1.086 2.193l2.428 .352a.39 .39 0 0 1 .217 .665l-1.757 1.707l.414 2.41a.39 .39 0 0 1 -.567 .411l-2.172 -1.138z" /><path d="M12 9.817l-2.172 1.138a.392 .392 0 0 1 -.568 -.41l.415 -2.411l-1.757 -1.707a.389 .389 0 0 1 .217 -.665l2.428 -.352l1.086 -2.193a.392 .392 0 0 1 .702 0l1.086 2.193l2.428 .352a.39 .39 0 0 1 .217 .665l-1.757 1.707l.414 2.41a.39 .39 0 0 1 -.567 .411l-2.172 -1.138z" /></svg>
                                <?php echo lang($messages, 'admin', 'account', 'table', 'buttons', 'is_admin'); ?>
                            </a>
							<?php } else { ?>
                            <a class="dropdown-item" href="#" onclick="removeAdmin('<?php echo $docs['g_id']; ?>');">
								<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17.373 13.371l.076 -.154a.392 .392 0 0 1 .702 0l.907 1.831m.367 .39c.498 .071 1.245 .18 2.24 .324a.39 .39 0 0 1 .217 .665c-.326 .316 -.57 .553 -.732 .712m-.611 3.405a.39 .39 0 0 1 -.567 .411l-2.172 -1.138l-2.172 1.138a.392 .392 0 0 1 -.568 -.41l.415 -2.411l-1.757 -1.707a.389 .389 0 0 1 .217 -.665l1.601 -.232" /><path d="M6.2 19.817l-2.172 1.138a.392 .392 0 0 1 -.568 -.41l.415 -2.411l-1.757 -1.707a.389 .389 0 0 1 .217 -.665l2.428 -.352l1.086 -2.193a.392 .392 0 0 1 .702 0l1.086 2.193l2.428 .352a.39 .39 0 0 1 .217 .665l-1.757 1.707l.414 2.41a.39 .39 0 0 1 -.567 .411l-2.172 -1.138z" /><path d="M9.557 5.556l1 -.146l1.086 -2.193a.392 .392 0 0 1 .702 0l1.086 2.193l2.428 .352a.39 .39 0 0 1 .217 .665l-1.757 1.707l.414 2.41a.39 .39 0 0 1 -.014 .187m-4.153 -.166l-.744 .39a.392 .392 0 0 1 -.568 -.41l.188 -1.093" /><path d="M3 3l18 18" /></svg>
                                <?php echo lang($messages, 'admin', 'account', 'table', 'buttons', 'remove_admin'); ?>
                            </a>
							
							<?php } ?>
							<?php } ?>
							<?php } else { ?>
                            <a class="dropdown-item" href="#" onclick="unsuspend('<?php echo $docs['id']; ?>');">
								<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11l3 3l8 -8" /><path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9" /></svg>
                                <?php echo lang($messages, 'admin', 'account', 'table', 'buttons', 'unsuspend'); ?>
                            </a>
							<?php } ?>
							
                            <a class="dropdown-item" href="#" onclick="deleteUser('<?php echo $docs['id']; ?>');">
								<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                <?php echo lang($messages, 'admin', 'account', 'table', 'buttons', 'delete'); ?>
                            </a>
                        </div>
                    </span>
                </div>
				<?php } else { ?>
                <div class="btn-list flex-nowrap">
					<span class="dropdown">
                        <button class="btn btn-ghost-success align-text-top">
							<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon me-0"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" /></svg>
						</button>
                    </span>
                </div>
				<?php } ?>
            </td>
        </tr>
	<?php
		}
	} else echo '<tr><td colspan="12">' . lang($messages, 'error', 'not_results_found') . '</td></tr>';
	$html = ob_get_clean();
	
	$totalRegistros = $plataformSQL->rowCount();

	$inicio = (($compag - 1) * $total) + 1;
	$fin = min($inicio + $total - 1, $totalRegistros);
	
	ob_start();
	echo paginationButtons($TotalRegistro, $compag, $total, 'updatePage');
	$paginations_list = ob_get_clean();
	
	$data = [
		'totalRegistros' => $totalRegistros,
		'inicio' => $inicio,
		'fin' => $fin,
		'html' => $html,
		'paginations_list' => $paginations_list,
	];

	echo json_encode($data);
}

if ($request == 'admin_wallet') {
	$cuentaid = $_POST['cuentaID']; 
	$search = (!empty($_POST['search'])) ? $_POST['search'] : '';
	$where = (!empty($_POST['where'])) ? $_POST['where'] : 0;
	$pagination = (!empty($_POST['pag'])) ? $_POST['pag'] : 1;
	$total = (!empty($_POST['total'])) ? $_POST['total'] : 100;	
	$wheres = ($where == 1) ? "ORDER BY id " : "ORDER BY id DESC";
	$compag = (int)(!isset($pagination)) ? 1 : $pagination;
		
	$params = (!empty($search)) ? ['%' . $search . '%'] : [];
	$searching = (!empty($search)) ? "WHERE `name` LIKE ?" : " ";

	$plataformSQL = $connx->prepare("SELECT * FROM `mcs_wallet` $searching");
	$plataformSQL->execute($params);
	$TotalRegistro = ceil($plataformSQL->RowCount() / $total);
	
	ob_start();
	$docsSQL = $connx->prepare("SELECT * FROM `mcs_wallet` $searching $wheres LIMIT " . (($compag - 1) * $total) . " , " . $total);
	$docsSQL->execute($params);
	if ($docsSQL->RowCount() > 0) {
		while ($docs = $docsSQL->fetch(PDO::FETCH_ASSOC)) {
			
		$usersSQL = $connx->prepare("SELECT COUNT(id) AS total FROM `mcs_wallet_user` WHERE `wallet` = ?;");
		$usersSQL->execute([$docs['id']]);
		$users = $usersSQL->fetch(PDO::FETCH_ASSOC);
					$cCuentaSQL = $connx->prepare("SELECT COUNT(id) as total FROM `mcs_cuentas` WHERE `wallet` = ?;");
					$cCuentaSQL->execute([$docs['id']]);
					$cCuenta = $cCuentaSQL->fetch(PDO::FETCH_ASSOC);
					
					$receiptSQL = $connx->prepare("SELECT COUNT(id) as total FROM `mcs_pagos` WHERE `wallet` = ?;");
					$receiptSQL->execute([$docs['id']]);
					$receipt = $receiptSQL->fetch(PDO::FETCH_ASSOC);
					
			
					$saldo = 0;
					$articulosSQL = $connx->prepare("SELECT * FROM `mcs_articulos` WHERE `wallet` = ?;");
					$articulosSQL->execute([$docs['id']]);
					while ($articulos = $articulosSQL->fetch(PDO::FETCH_ASSOC)) {
						if ($articulos['estado'] == NULL) {
							$saldo += $articulos['total'];
						}
					}		

					$saldoPay = 0;
					$pagosSQL = $connx->prepare("SELECT SUM(total) AS pagado FROM `mcs_pagos` WHERE `wallet` = ?;");
					$pagosSQL->execute([$docs['id']]);
					$pagos = $pagosSQL->fetch(PDO::FETCH_ASSOC);
					$saldoPay = $pagos['pagado'];

					$saldos = $saldo - $saldoPay;
					$options = explode(', ', $docs['options']); 
					$infinity_symbol = '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="text-success icon icon-tabler icons-tabler-outline icon-tabler-infinity"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9.828 9.172a4 4 0 1 0 0 5.656a10 10 0 0 0 2.172 -2.828a10 10 0 0 1 2.172 -2.828a4 4 0 1 1 0 5.656a10 10 0 0 1 -2.172 -2.828a10 10 0 0 0 -2.172 -2.828" /></svg>';
	?>
		<tr>
            <td data-label="<?php echo lang($messages, 'admin', 'wallet', 'table', 'user'); ?>">
				<div class="d-flex py-1 align-items-center">
                    <span class="avatar me-2" style="background-image: url(<?php echo IMAGE_ICON; ?>)"></span>
                    <div class="flex-fill">
                        <div class="font-weight-medium"><?php echo $docs['name']; ?></div>
                    </div>
                </div>
			</td>
            <td data-label="<?php echo lang($messages, 'admin', 'wallet', 'table', 'users'); ?>"><?php echo $users['total']; ?></td>
            <td data-label="<?php echo lang($messages, 'admin', 'wallet', 'table', 'account'); ?>"><?php echo $cCuenta['total']; ?><?php echo ($options[0] != '-1') ? '/' . $options[0] : '/' . $infinity_symbol; ?></td>
            <td data-label="<?php echo lang($messages, 'admin', 'wallet', 'table', 'balance'); ?>">$<?php echo number_format($saldos, 0, ',', '.'); ?><?php echo ($options[1] != '-1') ? '/$' . number_format($options[1], 0, ',', '.') : '/' . $infinity_symbol; ?></td>
            <td data-label="<?php echo lang($messages, 'admin', 'wallet', 'table', 'receipt'); ?>"><?php echo $receipt['total']; ?><?php echo ($options[2] != '-1') ? '/' . $options[2] : '/' . $infinity_symbol; ?></td>
            <td data-label="<?php echo lang($messages, 'admin', 'wallet', 'table', 'status', 'title'); ?>">
				<?php echo ($docs['status'] == 2) ? '<b class="text-danger">' . lang($messages, 'admin', 'wallet', 'table', 'status', 'suspend') . '</b>' :
				(($docs['status']) ? '<b class="text-success">' . lang($messages, 'admin', 'wallet', 'table', 'status', 'active') . '</b>' : '<b class="text-warning">' . lang($messages, 'admin', 'account', 'table', 'status', 'verify') . '</b>'); ?>
			</td>
            <td class="text-secondary" data-label="<?php echo lang($messages, 'admin', 'wallet', 'table', 'registered'); ?>"><?php echo counttime($docs['since']); ?></td>
            <td>
                <div class="btn-list flex-nowrap">
					<span class="dropdown">
                        <button class="btn dropdown-toggle align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown" aria-expanded="true">
							<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12h2" /><path d="M17 12h2" /><path d="M11 12h2" /></svg>
						</button>
                        <div class="dropdown-menu dropdown-menu-end" data-popper-placement="top-end" data-popper-reference-hidden="" data-popper-escaped="" style="position: absolute; inset: auto 0px 0px auto; margin: 0px; transform: translate(0px, -2px);">
                            
							<?php if ($docs['status']) { ?>
							<a class="dropdown-item" href="#" onclick="manageWallet('<?php echo $docs['id']; ?>');">
								<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 8v-2a2 2 0 0 1 2 -2h7a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-2" /><path d="M3 12h13l-3 -3" /><path d="M13 15l3 -3" /></svg>
                                <?php echo lang($messages, 'admin', 'wallet', 'table', 'buttons', 'manage'); ?>
                            </a>
							<?php } ?>
							
                            <a class="dropdown-item" href="#" onclick="checkWallet('<?php echo $docs['id']; ?>', '<?php echo $docs['name']; ?>');">
								<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
                                <?php echo lang($messages, 'admin', 'wallet', 'table', 'buttons', 'edit'); ?>
                            </a>
							
							<?php if ($docs['status'] != 2) { ?>
                            <a class="dropdown-item" href="#" onclick="suspend('<?php echo $docs['id']; ?>');">
								<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M5.7 5.7l12.6 12.6" /></svg>
                                <?php echo lang($messages, 'admin', 'wallet', 'table', 'buttons', 'suspend'); ?>
                            </a>
							<?php } else { ?>
                            <a class="dropdown-item" href="#" onclick="unsuspend('<?php echo $docs['id']; ?>');">
								<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11l3 3l8 -8" /><path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9" /></svg>
                                <?php echo lang($messages, 'admin', 'wallet', 'table', 'buttons', 'unsuspend'); ?>
                            </a>
							<?php } ?>
							
                            <a class="dropdown-item" href="#" onclick="deleteWallet('<?php echo $docs['id']; ?>');">
								<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                <?php echo lang($messages, 'admin', 'wallet', 'table', 'buttons', 'delete'); ?>
                            </a>
                        </div>
                    </span>
                </div>
				
            </td>
        </tr>
	<?php
		}
	} else echo '<tr><td colspan="12">' . lang($messages, 'error', 'not_results_found') . '</td></tr>';
	$html = ob_get_clean();
	
	$totalRegistros = $plataformSQL->rowCount();

	$inicio = (($compag - 1) * $total) + 1;
	$fin = min($inicio + $total - 1, $totalRegistros);
	
	ob_start();
	echo paginationButtons($TotalRegistro, $compag, $total, 'updatePage');
	$paginations_list = ob_get_clean();
	
	$data = [
		'totalRegistros' => $totalRegistros,
		'inicio' => $inicio,
		'fin' => $fin,
		'html' => $html,
		'paginations_list' => $paginations_list,
	];

	echo json_encode($data);
}

if ($request == 'admin_request_wallet') {
	$cuentaid = $_POST['cuentaID']; 
	$search = (!empty($_POST['search'])) ? $_POST['search'] : '';
	$where = (!empty($_POST['where'])) ? $_POST['where'] : 0;
	$pagination = (!empty($_POST['pag'])) ? $_POST['pag'] : 1;
	$total = (!empty($_POST['total'])) ? $_POST['total'] : 100;	
	$wheres = ($where == 1) ? "ORDER BY id " : "ORDER BY id DESC";
	$compag = (int)(!isset($pagination)) ? 1 : $pagination;
		
	$params = (!empty($search)) ? ['%' . $search . '%'] : [];
	$searching = (!empty($search)) ? "WHERE `status` = '0' AND `name` LIKE ?" : "WHERE `status` = '0'";

	$plataformSQL = $connx->prepare("SELECT * FROM `mcs_wallet` $searching");
	$plataformSQL->execute($params);
	$TotalRegistro = ceil($plataformSQL->RowCount() / $total);
	
	ob_start();
	$docsSQL = $connx->prepare("SELECT * FROM `mcs_wallet` $searching $wheres LIMIT " . (($compag - 1) * $total) . " , " . $total);
	$docsSQL->execute($params);
	if ($docsSQL->RowCount() > 0) {
		while ($docs = $docsSQL->fetch(PDO::FETCH_ASSOC)) {
		
		$usersWalletSQL = $connx->prepare("SELECT user FROM `mcs_wallet_user` WHERE `wallet` = ?;");
		$usersWalletSQL->execute([$docs['id']]);
		$usersWallet = $usersWalletSQL->fetch(PDO::FETCH_ASSOC);
		
		$usersSQL = $connx->prepare("SELECT * FROM `mcs_user` WHERE `id` = ?;");
		$usersSQL->execute([$usersWallet['user']]);
		$users = $usersSQL->fetch(PDO::FETCH_ASSOC);

	?>
		<tr>
            <td data-label="<?php echo lang($messages, 'admin', 'request', 'table', 'wallet', 'name'); ?>">
				<div class="d-flex py-1 align-items-center">
                    <span class="avatar me-2" style="background-image: url(<?php echo IMAGE_ICON; ?>)"></span>
                    <div class="flex-fill">
                        <div class="font-weight-medium"><?php echo $docs['name']; ?></div>
                    </div>
                </div>
			</td>
            <td data-label="<?php echo lang($messages, 'admin', 'request', 'table', 'wallet', 'owner'); ?>">
				<div class="d-flex py-1 align-items-center">
                    <span class="avatar me-2" style="background-image: url(<?php echo $users['avatar']; ?>)"></span>
                    <div class="flex-fill">
                        <div class="font-weight-medium"><?php echo $users['name']; ?></div>
                    </div>
                </div>
			</td>
            <td data-label="<?php echo lang($messages, 'admin', 'request', 'table', 'wallet', 'status', 'title'); ?>">
				<?php echo ($docs['status'] == 2) ? '<b class="text-danger">' . lang($messages, 'admin', 'request', 'table', 'wallet', 'status', 'suspend') . '</b>' :
				(($docs['status']) ? '<b class="text-success">' . lang($messages, 'admin', 'request', 'table', 'wallet', 'status', 'active') . '</b>' : '<b class="text-warning">' . lang($messages, 'admin', 'request', 'table', 'wallet', 'status', 'verify') . '</b>'); ?>
			</td>
            <td class="text-secondary" data-label="<?php echo lang($messages, 'admin', 'request', 'table', 'wallet', 'registered'); ?>"><?php echo counttime($docs['since']); ?></td>
            <td>
                <div class="btn-list flex-nowrap">
                    <button class="btn align-text-top" onclick="checkWallet('<?php echo $docs['id']; ?>', '<?php echo $docs['name']; ?>');">
						<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-checkbox"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11l3 3l8 -8" /><path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9" /></svg>
						<?php echo lang($messages, 'admin', 'request', 'table', 'wallet', 'buttons', 'verify'); ?>
					</button>
                    <button class="btn align-text-top" onclick="deleteWallet('<?php echo $docs['id']; ?>');">
						<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
						<?php echo lang($messages, 'admin', 'request', 'table', 'wallet', 'buttons', 'delete'); ?>
					</button>
                </div>
				
            </td>
        </tr>
	<?php
		}
	} else echo '<tr><td colspan="12">' . lang($messages, 'error', 'not_results_found') . '</td></tr>';
	$html = ob_get_clean();
	
	$totalRegistros = $plataformSQL->rowCount();

	$inicio = (($compag - 1) * $total) + 1;
	$fin = min($inicio + $total - 1, $totalRegistros);
	
	ob_start();
	echo paginationButtons($TotalRegistro, $compag, $total, 'updatePage');
	$paginations_list = ob_get_clean();
	
	$data = [
		'totalRegistros' => $totalRegistros,
		'inicio' => $inicio,
		'fin' => $fin,
		'html' => $html,
		'paginations_list' => $paginations_list,
	];

	echo json_encode($data);
}

if ($request == 'admin_request_account') {
	$cuentaid = $_POST['cuentaID']; 
	$search = (!empty($_POST['search'])) ? $_POST['search'] : '';
	$where = (!empty($_POST['where'])) ? $_POST['where'] : 0;
	$pagination = (!empty($_POST['pag'])) ? $_POST['pag'] : 1;
	$total = (!empty($_POST['total'])) ? $_POST['total'] : 100;	
	$wheres = ($where == 1) ? "ORDER BY id " : "ORDER BY id DESC";
	$compag = (int)(!isset($pagination)) ? 1 : $pagination;
		
	$params = (!empty($search)) ? ['%' . $search . '%'] : [];
	$searching = (!empty($search)) ? "WHERE `status` = '0' AND `name` LIKE ?" : "WHERE `status` = '0'";

	$plataformSQL = $connx->prepare("SELECT * FROM `mcs_user` $searching");
	$plataformSQL->execute($params);
	$TotalRegistro = ceil($plataformSQL->RowCount() / $total);
	
	ob_start();
	$docsSQL = $connx->prepare("SELECT * FROM `mcs_user` $searching $wheres LIMIT " . (($compag - 1) * $total) . " , " . $total);
	$docsSQL->execute($params);
	if ($docsSQL->RowCount() > 0) {
		while ($docs = $docsSQL->fetch(PDO::FETCH_ASSOC)) {

	?>
		<tr>
            <td data-label="<?php echo lang($messages, 'admin', 'request', 'table', 'account', 'owner'); ?>">
				<div class="d-flex py-1 align-items-center">
                    <span class="avatar me-2" style="background-image: url(<?php echo $docs['avatar']; ?>)"></span>
                    <div class="flex-fill">
                        <div class="font-weight-medium"><?php echo $docs['name']; ?></div>
						<div class="text-secondary"><?php echo $docs['email']; ?></div>
                    </div>
                </div>
			</td>
            <td data-label="<?php echo lang($messages, 'admin', 'request', 'table', 'account', 'gid'); ?>"><?php echo $docs['g_id']; ?></td>
            <td data-label="<?php echo lang($messages, 'admin', 'request', 'table', 'account', 'status', 'title'); ?>">
				<?php echo ($docs['status'] == 2) ? '<b class="text-danger">' . lang($messages, 'admin', 'request', 'table', 'account', 'status', 'suspend') . '</b>' :
				(($docs['status']) ? '<b class="text-success">' . lang($messages, 'admin', 'request', 'table', 'account', 'status', 'active') . '</b>' : '<b class="text-warning">' . lang($messages, 'admin', 'request', 'table', 'account', 'status', 'verify') . '</b>'); ?>
			</td>
            <td class="text-secondary" data-label="<?php echo lang($messages, 'admin', 'request', 'table', 'account', 'registered'); ?>"><?php echo counttime($docs['since']); ?></td>
            <td>
                <div class="btn-list flex-nowrap">
                    <button class="btn align-text-top" onclick="checkUser('<?php echo $docs['id']; ?>', '<?php echo $docs['name']; ?>', '<?php echo $docs['avatar']; ?>');">
						<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-checkbox"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11l3 3l8 -8" /><path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9" /></svg>
						<?php echo lang($messages, 'admin', 'request', 'table', 'account', 'buttons', 'verify'); ?>
					</button>
                    <button class="btn align-text-top" onclick="deleteUser('<?php echo $docs['id']; ?>');">
						<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
						<?php echo lang($messages, 'admin', 'request', 'table', 'account', 'buttons', 'delete'); ?>
					</button>
                </div>
				
            </td>
        </tr>
	<?php
		}
	} else echo '<tr><td colspan="12">' . lang($messages, 'error', 'not_results_found') . '</td></tr>';
	$html = ob_get_clean();
	
	$totalRegistros = $plataformSQL->rowCount();

	$inicio = (($compag - 1) * $total) + 1;
	$fin = min($inicio + $total - 1, $totalRegistros);
	
	ob_start();
	echo paginationButtons($TotalRegistro, $compag, $total, 'updatePage');
	$paginations_list = ob_get_clean();
	
	$data = [
		'totalRegistros' => $totalRegistros,
		'inicio' => $inicio,
		'fin' => $fin,
		'html' => $html,
		'paginations_list' => $paginations_list,
	];

	echo json_encode($data);
}
?>
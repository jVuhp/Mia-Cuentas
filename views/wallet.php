<?php

$wallet_active = 0;
$wallet_inactive = 0;

$walletUserSQLs = $connx->prepare("SELECT * FROM `mcs_wallet_user` WHERE `user` = ?;");
$walletUserSQLs->execute([$_SESSION['mcs_user']['id']]);
while($walletUsers = $walletUserSQLs->fetch(PDO::FETCH_ASSOC)) {
	
	$activeSQL = $connx->prepare("SELECT COUNT(id) AS total FROM `mcs_wallet` WHERE `id` = ? AND `status` = '1';");
	$activeSQL->execute([$walletUsers['wallet']]);
	$active = $activeSQL->fetch(PDO::FETCH_ASSOC);
	
	$wallet_active += $active['total'];

	$inactiveSQL = $connx->prepare("SELECT COUNT(id) AS total FROM `mcs_wallet` WHERE `id` = ? AND `status` = '0';");
	$inactiveSQL->execute([$walletUsers['wallet']]);
	$inactives = $inactiveSQL->fetch(PDO::FETCH_ASSOC);
	
	$wallet_inactive += $inactives['total'];
}

?>
<div class="page-header d-print-none">
          <div class="container-xl">
            <div class="row g-2 align-items-center">
              <div class="col">
                <h2 class="page-title"><?php echo lang($messages, 'wallet', 'title'); ?></h2>
                <div class="page-pretitle"><?php echo lang($messages, 'wallet', 'subtitle'); ?></div>
              </div>
              <div class="col-auto ms-auto d-print-none"></div>
            </div>
          </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards mb-3">
			<div class="col-sm-12 col-lg-6">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="subheader"><?php echo lang($messages, 'wallet', 'card', 'active'); ?></div>
                      <div class="ms-auto lh-1"></div>
                    </div>
                    <div class="d-flex align-items-baseline">
                      <div class="h1 mb-0 me-2"><?php echo number_format($wallet_active, 0, ',', '.'); ?></div>
                      <div class="me-auto"></div>
                    </div>
                  </div>
                </div>
            </div>
			<div class="col-sm-12 col-lg-6">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="subheader"><?php echo lang($messages, 'wallet', 'card', 'inprocess'); ?></div>
                      <div class="ms-auto lh-1"></div>
                    </div>
                    <div class="d-flex align-items-baseline">
                      <div class="h1 mb-0 me-2"><?php echo number_format($wallet_inactive, 0, ',', '.'); ?></div>
                      <div class="me-auto"></div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
			
        <div class="row row-deck row-cards">
			<?php
			$walletUserSQL = $connx->prepare("SELECT * FROM `mcs_wallet_user` WHERE `user` = ? ORDER BY id DESC;");
			$walletUserSQL->execute([$_SESSION['mcs_user']['id']]);
			
			if ($walletUserSQL->RowCount() > 0) {
				while($walletUser = $walletUserSQL->fetch(PDO::FETCH_ASSOC)) {
			
			$walletSQL = $connx->prepare("SELECT * FROM `mcs_wallet` WHERE `id` = ?;");
			$walletSQL->execute([$walletUser['wallet']]);
			if ($walletSQL->RowCount() > 0) {
				while($wallet = $walletSQL->fetch(PDO::FETCH_ASSOC)) {
					
					
					$cCuentaSQL = $connx->prepare("SELECT COUNT(id) as total FROM `mcs_cuentas` WHERE `wallet` = ?;");
					$cCuentaSQL->execute([$wallet['id']]);
					$cCuenta = $cCuentaSQL->fetch(PDO::FETCH_ASSOC);
					
					$receiptSQL = $connx->prepare("SELECT COUNT(id) as total FROM `mcs_pagos` WHERE `wallet` = ?;");
					$receiptSQL->execute([$wallet['id']]);
					$receipt = $receiptSQL->fetch(PDO::FETCH_ASSOC);
					
			
					$saldo = 0;
					$articulosSQL = $connx->prepare("SELECT * FROM `mcs_articulos` WHERE `wallet` = ?;");
					$articulosSQL->execute([$wallet['id']]);
					while ($articulos = $articulosSQL->fetch(PDO::FETCH_ASSOC)) {
						if ($articulos['estado'] == NULL) {
							$saldo += $articulos['total'];
						}
					}		

					$saldoPay = 0;
					$pagosSQL = $connx->prepare("SELECT SUM(total) AS pagado FROM `mcs_pagos` WHERE `wallet` = ?;");
					$pagosSQL->execute([$wallet['id']]);
					$pagos = $pagosSQL->fetch(PDO::FETCH_ASSOC);
					$saldoPay = $pagos['pagado'];

					$saldos = $saldo - $saldoPay;
					$options = explode(', ', $wallet['options']); 
					$infinity_symbol = '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="text-success icon icon-tabler icons-tabler-outline icon-tabler-infinity"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9.828 9.172a4 4 0 1 0 0 5.656a10 10 0 0 0 2.172 -2.828a10 10 0 0 1 2.172 -2.828a4 4 0 1 1 0 5.656a10 10 0 0 1 -2.172 -2.828a10 10 0 0 0 -2.172 -2.828" /></svg>';
			?>
			<div class="col-md-6 col-lg-3">
                <div class="card card-link card-link-pop bg-primary-lt">
					<?php
						echo ($wallet['status'] == 2) ? 
							'<div class="ribbon ribbon-top bg-red">
								<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-ban"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M5.7 5.7l12.6 12.6" /></svg>
							</div>' : 
							(($wallet['status']) ? 
								'<div class="ribbon ribbon-top bg-yellow">
									<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-zoom-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /><path d="M7 10l2 2l4 -4" /></svg>
								</div>' : 
								''
							);
					?>
					
					<div class="img-responsive img-responsive-21x9 card-img-top" style="background-image: url(<?php echo URI; ?>/static/img/macc-banner.jpg)"></div>
					<div class="card-body">
						<h3 class="card-title"><?php echo $wallet['name']; ?></h3>
						<p class="text-secondary">
							<span class="text-warning"><?php echo lang($messages, 'wallet', 'table', 'accounts'); ?></span> <?php echo $cCuenta['total']; ?><?php echo ($options[0] != '-1') ? '/' . $options[0] : '/' . $infinity_symbol; ?><br>
							<span class="text-warning"><?php echo lang($messages, 'wallet', 'table', 'balance'); ?></span> $<?php echo number_format($saldos, 0, ',', '.'); ?><?php echo ($options[1] != '-1') ? '/$' . number_format($options[1], 0, ',', '.') : '/' . $infinity_symbol; ?><br>
							<span class="text-warning"><?php echo lang($messages, 'wallet', 'table', 'receipt'); ?></span> <?php echo $receipt['total']; ?><?php echo ($options[2] != '-1') ? '/' . $options[2] : '/' . $infinity_symbol; ?><br>
							<span class="text-warning"><?php echo lang($messages, 'wallet', 'table', 'created_at'); ?></span> <?php echo date('j/m/Y', strtotime($wallet['since'])); ?>
						</p>
						<b class="text-primary">
						<?php
						echo ($wallet['status'] == 2) ? 
							lang($messages, 'wallet', 'table', 'verified', 'suspend') : 
							(($wallet['status']) ? 
								lang($messages, 'wallet', 'table', 'verified', 'verified') : 
								lang($messages, 'wallet', 'table', 'verified', 'process')
							);
						?>
						</b>
					</div>
					<div class="card-footer card-footer-transparent">
						<?php 
						if ($wallet['status'] == 2) {
						?>
						<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-exclamation-circle"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 9v4" /><path d="M12 16v.01" /></svg>
						<?php echo lang($messages, 'wallet', 'table', 'verified', 'suspend'); ?>
						<?php
						} else if ($wallet['status']) {
						if ($_SESSION['mcs_wallet']['id'] == $wallet['id']) { 
						?>
						<button class="btn btn-ghost-success btn-md">
							<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-circle-dashed-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8.56 3.69a9 9 0 0 0 -2.92 1.95" /><path d="M3.69 8.56a9 9 0 0 0 -.69 3.44" /><path d="M3.69 15.44a9 9 0 0 0 1.95 2.92" /><path d="M8.56 20.31a9 9 0 0 0 3.44 .69" /><path d="M15.44 20.31a9 9 0 0 0 2.92 -1.95" /><path d="M20.31 15.44a9 9 0 0 0 .69 -3.44" /><path d="M20.31 8.56a9 9 0 0 0 -1.95 -2.92" /><path d="M15.44 3.69a9 9 0 0 0 -3.44 -.69" /><path d="M9 12l2 2l4 -4" /></svg>
							<?php echo lang($messages, 'wallet', 'table', 'buttons', 'logged'); ?>
						</button>
						<?php } else { ?>
						<button class="btn btn-azure btn-md" onclick="manageWallet('<?php echo $wallet['id']; ?>');">
							<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 13m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M13.45 11.55l2.05 -2.05" /><path d="M6.4 20a9 9 0 1 1 11.2 0z" /></svg>
							<?php echo lang($messages, 'wallet', 'table', 'buttons', 'manage'); ?>
						</button>
						<?php 
						} 
						} else {
						?>
						<button class="btn btn-danger btn-md" onclick="deleteWallet('<?php echo $wallet['id']; ?>');">
							<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
							<?php echo lang($messages, 'wallet', 'table', 'buttons', 'delete'); ?>
						</button>
						<?php
						}
						
						?>
					</div>
                </div>
				
				
				
            </div>
			<?php
				}
			} else echo $page_not_found;
				}
			} else echo $page_not_found;
			
			?>
			
		</div>
	</div>
</div>
<script>
$(document).ready(function() {
	
    $('#createWallet').submit(function(e) {
		e.preventDefault();
		
        var formData = $(this).serializeArray();
		formData.push({ name: 'result', value: 'createWallet' });
		
		var i = 0;
		var expireValue = $('#newWalletName').val();
		if (expireValue.trim() === '') { $('#newWalletName').addClass('is-invalid'); i++; } else { $('#newWalletName').removeClass('is-invalid'); }
		if (i > 0) { return; }
		
        $.ajax({
            type: "POST",
            url: site_domain + '/execute/action.php',
            data: formData,
            success: function(response) {
                var jsonData = JSON.parse(response);
                if (jsonData.success == 1) {
					alertify.set('notifier','position', 'top-right');
					alertify.notify(jsonData.message, 'success', 5, function(){  console.log(jsonData.message); });
					location.reload();
                } else if (jsonData.success == 3) {
					alertify.set('notifier','position', 'top-right');
					alertify.notify(jsonData.message, 'error', 5, function(){  console.log(jsonData.message); });
				} else {
					alertify.set('notifier','position', 'top-right');
					alertify.notify(jsonData.message, 'error', 5, function(){  console.log(jsonData.message); });
                }
            }
       });

    });
	
	
    window.manageWallet = function(id) {
        var result = 'loginWallet';

        $.ajax({
            url: site_domain + '/execute/action.php',
            type: 'POST',
            data: { result: result, dataid: id },
            success: function(response) {
                var jsonData = JSON.parse(response);
				alertify.set('notifier','position', 'top-right');
				alertify.notify(jsonData.message, 'success', 5, function(){  console.log(jsonData.message); });
				if (jsonData.success === 1) {
					location.href='<?php echo URI; ?>/cuentas';
				}
			}
        });
    }
	
    window.deleteWallet = function(id) {
        var result = 'deleteWallet';

        $.ajax({
            url: site_domain + '/execute/action.php',
            type: 'POST',
            data: { result: result, dataid: id },
            success: function(response) {
                var jsonData = JSON.parse(response);
				alertify.set('notifier','position', 'top-right');
				alertify.notify(jsonData.message, 'success', 5, function(){  console.log(jsonData.message); });
				if (jsonData.success === 1) {
					location.reload();
				}
			}
        });
    }
	
});
</script>
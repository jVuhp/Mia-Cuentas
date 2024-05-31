<?php

if (!$page[1]) {

$saldo = 0;
$articulosSQL = $connx->prepare("SELECT * FROM `mcs_articulos` WHERE `wallet` = ?;");
$articulosSQL->execute([$_SESSION['mcs_wallet']['id']]);
while ($articulos = $articulosSQL->fetch(PDO::FETCH_ASSOC)) {
	if ($articulos['estado'] == NULL) {
		$saldo += $articulos['total'];
	}
}

$cuentasSQL = $connx->prepare("SELECT COUNT(id) AS total FROM `mcs_cuentas` WHERE `wallet` = ?;");
$cuentasSQL->execute([$_SESSION['mcs_wallet']['id']]);
$cuentaCount = $cuentasSQL->fetch(PDO::FETCH_ASSOC);

$recibosSQL = $connx->prepare("SELECT COUNT(id) AS total FROM `mcs_pagos` WHERE `wallet` = ?;");
$recibosSQL->execute([$_SESSION['mcs_wallet']['id']]);
$recibosCount = $recibosSQL->fetch(PDO::FETCH_ASSOC);

$cuentasPaySQL = $connx->prepare("SELECT SUM(total) AS total FROM `mcs_articulos` WHERE `wallet` = ? AND `estado` != NULL;");
$cuentasPaySQL->execute([$_SESSION['mcs_wallet']['id']]);
$cuentasPay = $cuentasPaySQL->fetch(PDO::FETCH_ASSOC);


$saldoPay = 0;
$pagosSQL = $connx->prepare("SELECT SUM(total) AS pagado FROM `mcs_pagos` WHERE `wallet` = ?;");
$pagosSQL->execute([$_SESSION['mcs_wallet']['id']]);
$pagos = $pagosSQL->fetch(PDO::FETCH_ASSOC);
$saldoPay = $pagos['pagado'];

$saldos = $saldo - $saldoPay;

$options_wallet = explode(', ', $wallet['options']);
?>

<div class="page-header d-print-none">
          <div class="container-xl">
            <div class="row g-2 align-items-center">
              <div class="col">
                <h2 class="page-title"><?php echo lang($messages, 'cuentas', 'title'); ?></h2>
                <div class="page-pretitle"><?php echo lang($messages, 'cuentas', 'subtitle'); ?></div>
              </div>
              <div class="col-auto ms-auto d-print-none">
                
				  
							<select class="form-control form-control-sm" id="total" hidden>
								<option value="500">500</option>
							</select>
              </div>
            </div>
          </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
			<div class="col-sm-12 col-lg-6">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="subheader"><?php echo lang($messages, 'cuentas', 'card', 'account'); ?></div>
                      <div class="ms-auto lh-1"></div>
                    </div>
                    <div class="d-flex align-items-baseline">
                      <div class="h1 mb-0 me-2"><?php echo number_format($cuentaCount['total'], 0, ',', '.'); ?>/<?php echo ($options_wallet[0] == '-1') ? $infinity_symbol : number_format($options_wallet[0], 0, ',', '.'); ?></div>
                      <div class="me-auto"></div>
                    </div>
                  </div>
                </div>
            </div>
			<div class="col-sm-12 col-lg-6">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="subheader"><?php echo lang($messages, 'cuentas', 'card', 'receipts'); ?></div>
                      <div class="ms-auto lh-1"></div>
                    </div>
                    <div class="d-flex align-items-baseline">
                      <div class="h1 mb-0 me-2"><?php echo number_format($recibosCount['total'], 0, ',', '.'); ?>/<?php echo ($options_wallet[2] == '-1') ? $infinity_symbol : number_format($options_wallet[2], 0, ',', '.'); ?></div>
                      <div class="me-auto"></div>
                    </div>
                  </div>
                </div>
            </div>
			<div class="col-sm-12 col-lg-12">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="subheader"><?php echo lang($messages, 'cuentas', 'card', 'total_balances'); ?></div>
                      <div class="ms-auto lh-1"></div>
                    </div>
                    <div class="d-flex align-items-baseline">
                      <div class="h1 mb-0 me-2">$<?php echo number_format($saldo, 2, ',', '.'); ?>/$<?php echo ($options_wallet[1] == '-1') ? $infinity_symbol : number_format($options_wallet[1], 2, ',', '.'); ?></div>
                      <div class="me-auto"></div>
                    </div>
                  </div>
                </div>
            </div>
			<div class="col-sm-12 col-lg-6">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="subheader"><?php echo lang($messages, 'cuentas', 'card', 'paid_balance'); ?></div>
                      <div class="ms-auto lh-1"></div>
                    </div>
                    <div class="d-flex align-items-baseline">
                      <div class="h1 mb-0 me-2">$<?php echo number_format($saldoPay, 2, ',', '.'); ?></div>
                      <div class="me-auto"></div>
                    </div>
                  </div>
                </div>
            </div>
			<div class="col-sm-12 col-lg-6">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="subheader"><?php echo lang($messages, 'cuentas', 'card', 'unpaid_balance'); ?></div>
                      <div class="ms-auto lh-1"></div>
                    </div>
                    <div class="d-flex align-items-baseline">
                      <div class="h1 mb-0 me-2">$<?php echo number_format($saldos, 2, ',', '.'); ?></div>
                      <div class="me-auto"></div>
                    </div>
                  </div>
                </div>
            </div>
			
			<div class="col-12">
				<div class="input-icon mb-1 w-100">
                    <span class="input-icon-addon">
						<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-search"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                    </span>
                    <input type="text" value="" class="form-control w-100" id="search" placeholder="<?php echo lang($messages, 'filters', 'search'); ?>" value="<?php echo $codeAuth[1]; ?>">
                    <input type="hidden" class="form-control form-control-sm" aria-label="Search invoice" id="where" value="0">
                    <input type="hidden" class="form-control form-control-sm" aria-label="Search invoice" id="paginationID" value="1">
                </div>
            </div>
			<div class="col-12">
                <div class="card">
                  <div class="table-responsive">
                    <table class="table table-vcenter table-mobile-md card-table">
                      <thead>
                        <tr>
                          <th><?php echo lang($messages, 'cuentas', 'table', 'head', 'account'); ?></th>
                          <th><?php echo lang($messages, 'cuentas', 'table', 'head', 'total_balance'); ?></th>
                          <th><?php echo lang($messages, 'cuentas', 'table', 'head', 'paid_balance'); ?></th>
                          <th><?php echo lang($messages, 'cuentas', 'table', 'head', 'debt_balance'); ?></th>
                          <th><?php echo lang($messages, 'cuentas', 'table', 'head', 'registered'); ?></th>
                          <th class="w-1"><?php echo lang($messages, 'cuentas', 'table', 'head', 'actions'); ?></th>
                        </tr>
                      </thead>
                      <tbody id="content_table"></tbody>
                    </table>
                  </div>
                </div>
            </div>
			<div class="row g-4 mb-3">
				<div class="col-lg-6 col-md-6 col-sm-12">
					<ul class="pagination m-0 ms-auto" id="pagination_list"></ul>
				</div>
				<div class="text-end col-lg-6 col-md-6 col-sm-12">
					<p class="m-0 text-secondary" id="pagination_info"></p>
				</div>
			</div>
			
		</div>
	</div>
</div>
<div class="modal modal-blur fade" id="inviteCopy" tabindex="-1" aria-modal="false" role="dialog">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="modal-status bg-success"></div>
          <div class="modal-body text-center py-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-green icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path><path d="M9 12l2 2l4 -4"></path></svg>
            <h3><?php echo lang($messages, 'cuentas', 'modal2', 'title'); ?></h3>
            <div class="text-secondary"><?php echo lang($messages, 'cuentas', 'modal2', 'subtitle'); ?></div>
          </div>
          <div class="modal-footer">
            <div class="w-100">
              <div class="row">
                <div class="col"><a href="#" class="btn w-100" data-bs-dismiss="modal">
                    <?php echo lang($messages, 'cuentas', 'modal2', 'button'); ?>
                  </a></div>
              </div>
            </div>
          </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
	
    var e = $('#total');
    var w = $('#where');
    var s = $('#search');

    window.updateAdminSQL = function() {
        var pag = $('#paginationID').val();
        var total = e.val();
        var where = w.val();
        var search = s.val();
        var result = 'cuentas';

        $.ajax({
            url: site_domain + '/execute/table.php',
            type: 'POST',
            data: { result: result, search: search, where: where, pag: pag, total: total },
            success: function(response) {
                var jsonData = JSON.parse(response);
				
				var lin1 = [':compag_to:', ':end:', ':results:'];
				var lin2 = [jsonData.inicio, jsonData.fin, jsonData.totalRegistros];
				var lin3 = '<?php echo lang($messages, 'filters', 'showing'); ?>';
				
                $('#content_table').html(jsonData.html);
                $('#pagination_info').text(str_replaces(lin1, lin2, lin3));
                $('#pagination_list').html(jsonData.paginations_list);
				
				var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
				var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
				  return new bootstrap.Tooltip(tooltipTriggerEl)
				})
            }
        });
    }

    e.change(updateAdminSQL);
    w.change(updateAdminSQL);
    s.change(updateAdminSQL);
    s.on('input', updateAdminSQL);
	
	updateAdminSQL();
});

</script>
<?php

} else {
	$cuentaSQL = $connx->prepare("SELECT * FROM `mcs_cuentas` WHERE `id` = ?");
	$cuentaSQL->execute([$page[1]]);
	if ($cuentaSQL->RowCount() > 0) {
	$cuenta = $cuentaSQL->fetch(PDO::FETCH_ASSOC);
	
	
	
$saldoPay = 0;
$saldoNPay = 0;
$pagosSQL = $connx->prepare("SELECT SUM(total) AS pagado FROM `mcs_pagos` WHERE `cuenta` = ?;");
$pagosSQL->execute([$page[1]]);
$pagos = $pagosSQL->fetch(PDO::FETCH_ASSOC);
$saldoPay = $pagos['pagado'];

$articulosPaySQL = $connx->prepare("SELECT * FROM `mcs_articulos` WHERE `cuenta` = ?;");
$articulosPaySQL->execute([$page[1]]);
while ($articulosPay = $articulosPaySQL->fetch(PDO::FETCH_ASSOC)) {
	if ($articulosPay['estado'] != NULL) {
		$saldoPay += $pagos['pagado'];
	} else {
		$saldoNPay += $articulosPay['total'];
	}
}
$saldoNPay = $saldoNPay - $pagos['pagado'];
?>

<div class="page-header d-print-none">
          <div class="container-xl">
            <div class="row g-2 align-items-center">
              <div class="col">
                <h2 class="page-title"><?php echo lang($messages, 'cuentas', 'articles', 'title'); ?><?php echo $cuenta['nombre']; ?></h2>
                <div class="page-pretitle"><?php echo lang($messages, 'cuentas', 'articles', 'subtitle'); ?></div>
              </div>
              <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                  <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#receipt">
					<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-file-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M12 11l0 6" /><path d="M9 14l6 0" /></svg>
                    <?php echo lang($messages, 'cuentas', 'articles', 'buttons', 'receipt'); ?>
                  </a>
                  <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#new_articles">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 5l0 14"></path><path d="M5 12l14 0"></path></svg>
                    <?php echo lang($messages, 'cuentas', 'articles', 'buttons', 'article'); ?>
                  </a>
                  <a href="#" class="btn btn-primary d-sm-none btn-icon" data-bs-toggle="modal" data-bs-target="#new_articles">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 5l0 14"></path><path d="M5 12l14 0"></path></svg>
                  </a>
							<select class="form-control form-control-sm" id="total" hidden>
								<option value="500">500</option>
							</select>
                </div>
              </div>
            </div>
          </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
			<div class="col-sm-6 col-lg-6">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="subheader"><?php echo lang($messages, 'cuentas', 'articles', 'card', 'paid', 'title'); ?></div>
                      <div class="ms-auto lh-1"></div>
                    </div>
                    <div class="d-flex align-items-baseline">
                      <div class="h1 mb-0 me-2">$<?php echo number_format($saldoPay, 2, ',', '.'); ?></div>
                      <div class="me-auto" id="receipt_btn"><a href="#" onclick="updateTables(); event.preventDefault();"><?php echo lang($messages, 'cuentas', 'articles', 'card', 'paid', 'button'); ?></a></div>
                    </div>
                  </div>
                </div>
            </div>
			<div class="col-sm-6 col-lg-6">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="subheader"><?php echo lang($messages, 'cuentas', 'articles', 'card', 'unpaid', 'title'); ?></div>
                      <div class="ms-auto lh-1"></div>
                    </div>
                    <div class="d-flex align-items-baseline">
                      <div class="h1 mb-0 me-2" id="debt_bal">$<?php echo number_format($saldoNPay, 2, ',', '.'); ?></div>
                      <div class="me-auto" id="articles_btn" hidden=""><a href="#" onclick="updateTables(); event.preventDefault();"><?php echo lang($messages, 'cuentas', 'articles', 'card', 'unpaid', 'button'); ?></a></div>
                    </div>
                  </div>
                </div>
            </div>
			
			<div class="col-12">
				<div class="input-icon mb-1 w-100">
                    <span class="input-icon-addon">
						<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-search"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                    </span>
                    <input type="text" value="" class="form-control w-100" placeholder="<?php echo lang($messages, 'filters', 'search'); ?>" id="search">
                    <input type="hidden" class="form-control form-control-sm" aria-label="Search invoice" id="where" value="0">
                    <input type="hidden" class="form-control form-control-sm" aria-label="Search invoice" id="paginationID" value="1">
                    <input type="hidden" class="form-control form-control-sm" aria-label="Search invoice" id="cuentaID" value="<?php echo $page[1]; ?>">
                </div>
            </div>
			
				<div class="col-12">
					<div class="card">
					  <div class="table-responsive">
						<table class="table table-vcenter table-mobile-md card-table">
						  <thead>
							<tr id="articles">
							  <th><?php echo lang($messages, 'cuentas', 'articles', 'table', 'head', 'article'); ?></th>
							  <th><?php echo lang($messages, 'cuentas', 'articles', 'table', 'head', 'balance'); ?></th>
							  <th><?php echo lang($messages, 'cuentas', 'articles', 'table', 'head', 'registered'); ?></th>
							  <th class="w-1"><?php echo lang($messages, 'cuentas', 'articles', 'table', 'head', 'actions'); ?></th>
							</tr>
							<tr id="receipts" hidden="">
							  <th><?php echo lang($messages, 'cuentas', 'receipt', 'table', 'head', 'description'); ?></th>
							  <th><?php echo lang($messages, 'cuentas', 'receipt', 'table', 'head', 'total'); ?></th>
							  <th><?php echo lang($messages, 'cuentas', 'receipt', 'table', 'head', 'registered'); ?></th>
							  <th class="w-1"><?php echo lang($messages, 'cuentas', 'receipt', 'table', 'head', 'actions'); ?></th>
							</tr>
						  </thead>
						  <tbody id="content_table"></tbody>
						</table>
					  </div>
					</div>
				</div>
				<div class="row g-4 mb-3">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<ul class="pagination m-0 ms-auto" id="pagination_list"></ul>
					</div>
					<div class="text-end col-lg-6 col-md-6 col-sm-12">
						<p class="m-0 text-secondary" id="pagination_info"></p>
					</div>
				</div>
			<input type="hidden" value="articulos" id="result_table">
		</div>
	</div>
</div>
    <div class="modal modal-blur fade" id="viewArticle" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<form method="POST" id="agregararticulo" class="modal-content">
			  <div class="modal-header">
				<h5 class="modal-title"><?php echo lang($messages, 'cuentas', 'articles', 'modal', 'new_article', 'title'); ?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			  </div>
			  <div class="modal-body">
				<div class="mb-2">
					<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon me-2 text-secondary"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7.5 7.5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M3 6v5.172a2 2 0 0 0 .586 1.414l7.71 7.71a2.41 2.41 0 0 0 3.408 0l5.592 -5.592a2.41 2.41 0 0 0 0 -3.408l-7.71 -7.71a2 2 0 0 0 -1.414 -.586h-5.172a3 3 0 0 0 -3 3z" /></svg>
                    Articulo: <strong id="art_articulo">Unknown</strong>
                </div>
				<div class="mb-2">
					<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon me-2 text-secondary"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2" /><path d="M14 8h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5m2 0v1.5m0 -9v1.5" /></svg>
                    Precio: <strong id="art_precio">2.000,00</strong>
                </div>
				<div class="mb-2">
					<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon me-2 text-secondary"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 7v5l3 3" /></svg>
                    Fecha: <strong id="art_fecha">27 de Feb, 2024</strong>
                </div>
			  </div>
			  <div class="modal-footer">
				<a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
				  <?php echo lang($messages, 'cuentas', 'articles', 'modal', 'new_article', 'buttons', 'cancel'); ?>
				</a>
				<button type="submit" class="btn btn-primary ms-auto" data-bs-dismiss="modal">
				  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
				  <?php echo lang($messages, 'cuentas', 'articles', 'modal', 'new_article', 'buttons', 'submit'); ?>
				</button>
			  </div>
			</form>
        </div>
    </div>
	
    <div class="modal modal-blur fade" id="new_articles" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<form method="POST" id="agregararticulo" class="modal-content">
			  <div class="modal-header">
				<h5 class="modal-title"><?php echo lang($messages, 'cuentas', 'articles', 'modal', 'new_article', 'title'); ?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			  </div>
			  <div class="modal-body">
				<div class="mb-3 search-container">
					<label class="form-label"><?php echo lang($messages, 'cuentas', 'articles', 'modal', 'new_article', 'article', 'label'); ?></label>
					<input type="text" class="form-control" name="articulo" id="articulo" placeholder="<?php echo lang($messages, 'cuentas', 'articles', 'modal', 'new_article', 'article', 'placeholder'); ?>">
					<ul id="clientResults" class="search-results"></ul>
				</div>
				<div class="mb-3">
				  <label class="form-label"><?php echo lang($messages, 'cuentas', 'articles', 'modal', 'new_article', 'cost', 'label'); ?></label>
				  <input type="text" class="form-control" name="costo" id="costo" placeholder="<?php echo lang($messages, 'cuentas', 'articles', 'modal', 'new_article', 'cost', 'placeholder'); ?>">
				  <input type="hidden" class="form-control" name="cuenta" id="cuenta" value="<?php echo $page[1]; ?>">
				</div>
			  </div>
			  <div class="modal-footer">
				<a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
				  <?php echo lang($messages, 'cuentas', 'articles', 'modal', 'new_article', 'buttons', 'cancel'); ?>
				</a>
				<button type="submit" class="btn btn-primary ms-auto" data-bs-dismiss="modal">
				  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
				  <?php echo lang($messages, 'cuentas', 'articles', 'modal', 'new_article', 'buttons', 'submit'); ?>
				</button>
			  </div>
			</form>
        </div>
    </div>
    <div class="modal modal-blur fade" id="receipt" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<form method="POST" id="crearrecibo" class="modal-content">
			  <div class="modal-header">
				<h5 class="modal-title"><?php echo lang($messages, 'cuentas', 'articles', 'modal', 'new_receipt', 'title'); ?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			  </div>
			  <div class="modal-body">
				<div class="mb-3">
				  <label class="form-label"><?php echo lang($messages, 'cuentas', 'articles', 'modal', 'new_receipt', 'cost', 'label'); ?></label>
				  <input type="text" class="form-control" name="costo" id="costos" placeholder="<?php echo lang($messages, 'cuentas', 'articles', 'modal', 'new_receipt', 'cost', 'placeholder'); ?>">
				  <input type="hidden" class="form-control" name="cuenta" id="cuenta" value="<?php echo $page[1]; ?>">
				  <p class="text-muted">
				  <?php 
				  
				  $var1s = ["%account_name%", "%unpaid:balance%"];
				  $var2s = [$cuenta['nombre'], number_format($saldoNPay, 2, ',', '.')];
				  
				  echo str_replace($var1s, $var2s, lang($messages, 'cuentas', 'articles', 'modal', 'new_receipt', 'cost', 'description')); 
				  
				  ?></p>
				</div>
			  </div>
			  <div class="modal-body">
				<div class="mb-3">
				  <label class="form-label"><?php echo lang($messages, 'cuentas', 'articles', 'modal', 'new_receipt', 'description', 'label'); ?></label>
				  <textarea class="form-control" name="descripcion" id="descripcion" placeholder="<?php echo lang($messages, 'cuentas', 'articles', 'modal', 'new_receipt', 'description', 'placeholder'); ?>"></textarea>
				  <p class="text-muted"><?php echo lang($messages, 'cuentas', 'articles', 'modal', 'new_receipt', 'description', 'description'); ?></p>
				</div>
			  </div>
			  <div class="modal-footer">
				<a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
				  <?php echo lang($messages, 'cuentas', 'articles', 'modal', 'new_receipt', 'buttons', 'cancel'); ?>
				</a>
				<button type="submit" class="btn btn-primary ms-auto" data-bs-dismiss="modal">
				  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
				  <?php echo lang($messages, 'cuentas', 'articles', 'modal', 'new_receipt', 'buttons', 'submit'); ?>
				</button>
			  </div>
			</form>
        </div>
    </div>
    <div class="modal modal-blur fade" id="misrecibos" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
			<form method="POST" id="misrecibos" class="modal-content">
			  <div class="modal-header">
				<h5 class="modal-title">Crear recibo de pago</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			  </div>
			  <div class="modal-body">
				<div class="mb-3">
				  <label class="form-label">Costo</label>
				  <input type="text" class="form-control" name="costo" id="costos" placeholder="(Ej: 8890)">
				  <input type="hidden" class="form-control" name="cuenta" id="cuenta" value="<?php echo $page[1]; ?>">
				  <p class="text-muted">El recibo funciona para descontar una suma de lo que se debe de <b><?php echo $cuenta['nombre']; ?></b>. Por ejemplo, Lo que se debe es <b>$<?php echo number_format($saldoNPay, 2, ',', '.'); ?></b></p>
				</div>
			  </div>
			  <div class="modal-body">
				<div class="mb-3">
				  <label class="form-label">Descripcion adicional</label>
				  <textarea class="form-control" name="descripcion" id="descripcion" placeholder="Ejemplo hola :D"></textarea>
				  <p class="text-muted">Alguna informacion que deseas dejar para informarte correctamente de este recibo de pago. Es opcional.</p>
				</div>
			  </div>
			  <div class="modal-footer">
				<a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
				  Cancelar
				</a>
				<button type="submit" class="btn btn-primary ms-auto" data-bs-dismiss="modal">
				  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
				  Finalizar
				</button>
			  </div>
			</form>
        </div>
    </div>

<script>
$(document).ready(function() {
	
	window.checkArticulo = function(dataid, name, precio, fecha) {
		$('#art_articulo').text(name);
		$('#art_precio').text(precio);
		$('#art_fecha').text(fecha);
		$('#viewArticle').modal('show');
	}
	
	var clientResults = $('#clientResults');
	$('#articulo').on('input', function() {
		var result = 'sub_search_client';
		var query = $(this).val();

		if (query.length >= 2) {
		  $.ajax({
			url: site_domain + '/execute/action.php',
			type: 'POST',
			data: { result: result, query: query },
			dataType: 'json',
			success: function(data) {
			  displayResults(data);
			  clientResults.show();
			},
			error: function(error) {
			  console.error('Error en la solicitud AJAX:', error);
			}
		  });
		} else {
			clientResults.hide();
		}
	});
	
	$(document).on('click', function(e) {
		if (!clientResults.is(e.target) && clientResults.has(e.target).length === 0) {
		  clientResults.hide();
		}
	});
	
	$('#clientResults').on('click', 'li', function() {
		var selectedName = $(this).text();
		var selectedUdid = $(this).data('total');
		var selectedNamed = $(this).data('name');

		$('#articulo').val(selectedNamed);
		$('#costo').val(selectedUdid);

		$('#clientResults').empty();
	});

	function displayResults(results) {
		var resultList = $('#clientResults');
		resultList.empty();

		$.each(results, function(index, result) {
		  var listItem = $('<li>').text(result.name + ' - ($' + result.total + ')').data({
			'name': result.name,
			'total': result.total
		  });
		  resultList.append(listItem);
		});
	}
	
    var e = $('#total');
    var w = $('#where');
    var s = $('#search');

    window.updateAdminSQL = function() {
        var pag = $('#paginationID').val();
        var cuentaID = $('#cuentaID').val();
        var total = e.val();
        var where = w.val();
        var search = s.val();
        var result = $('#result_table').val();

        $.ajax({
            url: site_domain + '/execute/table.php',
            type: 'POST',
            data: { result: result, search: search, where: where, pag: pag, total: total, cuentaID: cuentaID },
            success: function(response) {
                var jsonData = JSON.parse(response);
				
				var lin1 = [':compag_to:', ':end:', ':results:'];
				var lin2 = [jsonData.inicio, jsonData.fin, jsonData.totalRegistros];
				var lin3 = '<?php echo lang($messages, 'filters', 'showing'); ?>';
				
                $('#content_table').html(jsonData.html);
                $('#pagination_info').text(str_replaces(lin1, lin2, lin3));
                $('#pagination_list').html(jsonData.paginations_list);
                $('#debt_bal').html(jsonData.debt_bal);
            }
        });
    }

    e.change(updateAdminSQL);
    w.change(updateAdminSQL);
    s.change(updateAdminSQL);
    s.on('input', updateAdminSQL);
	
	updateAdminSQL();
	
    window.updateTables = function() {
		
		$('#search').val('');
		
		if ($('#result_table').val() === 'articulos') {
			$('#result_table').val('receipt');
			$('#receipts').removeAttr('hidden');
			$('#articles').attr('hidden', true);
			
			$('#articles_btn').removeAttr('hidden');
			$('#receipt_btn').attr('hidden', true);
		} else {
			$('#result_table').val('articulos');
			$('#receipts').attr('hidden', true);
			$('#articles').removeAttr('hidden');
			
			$('#receipt_btn').removeAttr('hidden');
			$('#articles_btn').attr('hidden', true);
		}
		updateAdminSQL();
    }
});

$(document).ready(function() {
    $('#agregararticulo').submit(function(e) {
		e.preventDefault();
		
        var formData = $(this).serializeArray();
		formData.push({ name: 'result', value: 'articulo_new' });
		
		var i = 0;
		var expireValue = $('#articulo').val();
		if (expireValue.trim() === '') { $('#articulo').addClass('is-invalid'); i++; } else { $('#articulo').removeClass('is-invalid'); }
		var expireValue = $('#costo').val();
		if (expireValue.trim() === '') { $('#costo').addClass('is-invalid'); i++; } else { $('#costo').removeClass('is-invalid'); }
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
					
					$('#articulo').val();
					$('#costo').val();
					
					updateAdminSQL();
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

    $('#crearrecibo').submit(function(e) {
		e.preventDefault();
		
        var formData = $(this).serializeArray();
		formData.push({ name: 'result', value: 'recibo' });
		
		var i = 0;
		var expireValue = $('#costos').val();
		if (expireValue.trim() === '') { $('#costos').addClass('is-invalid'); i++; } else { $('#costos').removeClass('is-invalid'); }
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
});
</script>
<?php
	} else echo $page_not_found;
}

?>
    <div class="modal modal-blur fade" id="modal-report" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <form method="POST" id="agregarcuenta" class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><?php echo lang($messages, 'cuentas', 'modal', 'title'); ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label"><?php echo lang($messages, 'cuentas', 'modal', 'box', 'label'); ?></label>
              <input type="text" class="form-control" name="nombre" id="nombre" placeholder="<?php echo lang($messages, 'cuentas', 'modal', 'box', 'placeholder'); ?>">
            </div>
          </div>
          <div class="modal-footer">
            <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
              <?php echo lang($messages, 'cuentas', 'modal', 'buttons', 'cancel'); ?>
            </a>
            <button type="submit" class="btn btn-primary ms-auto">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
              <?php echo lang($messages, 'cuentas', 'modal', 'buttons', 'submit'); ?>
            </button>
          </div>
        </div>
      </div>
    </div>
<script>
$(document).ready(function() {
    $('#agregarcuenta').submit(function(e) {
		e.preventDefault();
		
        var formData = $(this).serializeArray();
		formData.push({ name: 'result', value: 'inscribir' });
		
		var i = 0;
		var expireValue = $('#nombre').val();
		if (expireValue.trim() === '') { $('#nombre').addClass('is-invalid'); i++; } else { $('#nombre').removeClass('is-invalid'); }
		
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
					location.href = site_domain + '/cuentas/' + jsonData.id;
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
});
</script>
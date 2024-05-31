<?php

if (!$page[1]) {
	
?>

<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title"><?php echo lang($messages, 'admin', 'index', 'title'); ?></h2>
                <div class="page-pretitle"><?php echo lang($messages, 'admin', 'index', 'subtitle'); ?></div>
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
                      <div class="h1 mb-0 me-2"><?php echo number_format($cuentaCount['total'], 0, ',', '.'); ?></div>
                      <div class="me-auto"></div>
                    </div>
                  </div>
                </div>
            </div>
			<div class="col-sm-12 col-lg-6">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="subheader"><?php echo lang($messages, 'cuentas', 'card', 'total_balances'); ?></div>
                      <div class="ms-auto lh-1"></div>
                    </div>
                    <div class="d-flex align-items-baseline">
                      <div class="h1 mb-0 me-2">$<?php echo number_format($saldo, 2, ',', '.'); ?></div>
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
                      <div class="h1 mb-0 me-2">$<?php echo number_format($saldoPay, 0, ',', '.'); ?></div>
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

} else if ($page[1] == 'request') {
	
	$accPendingSQL = $connx->prepare("SELECT COUNT(id) AS total FROM `mcs_user` WHERE `status` = '0';");
	$accPendingSQL->execute();
	$accPending = $accPendingSQL->fetch(PDO::FETCH_ASSOC);
	
	$wallPendingSQL = $connx->prepare("SELECT COUNT(id) AS total FROM `mcs_wallet` WHERE `status` = '0';");
	$wallPendingSQL->execute();
	$wallPending = $wallPendingSQL->fetch(PDO::FETCH_ASSOC);
	
	if (!$page[2]) {
?>

<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title"><?php echo lang($messages, 'admin', 'request', 'title'); ?></h2>
                <div class="page-pretitle"><?php echo lang($messages, 'admin', 'request', 'subtitle'); ?></div>
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
			
			<div class="col-12">
				<div class="card">
                  <div class="card-body">
                    <ul class="pagination ">
                      <li class="page-item page-prev disabled">
                        <a class="page-link" href="<?php echo URI; ?>/admin/request" tabindex="-1" aria-disabled="true">
                          <div class="page-item-subtitle"><?php echo lang($messages, 'admin', 'request', 'buttons', 'wallet', 'title'); ?></div>
                          <div class="page-item-title">
							<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-wallet"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" /><path d="M20 12v4h-4a2 2 0 0 1 0 -4h4" /></svg>
							<?php echo lang($messages, 'admin', 'request', 'buttons', 'wallet', 'subtitle'); ?>
							<span class="badge bg-teal text-teal-fg ms-2"><?php echo $wallPending['total']; ?></span>
						  </div>
                        </a>
                      </li>
                      <li class="page-item page-next">
                        <a class="page-link" href="<?php echo URI; ?>/admin/request/accounts">
                          <div class="page-item-subtitle"><?php echo lang($messages, 'admin', 'request', 'buttons', 'account', 'title'); ?></div>
                          <div class="page-item-title">
							<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
							<?php echo lang($messages, 'admin', 'request', 'buttons', 'account', 'subtitle'); ?>
							<span class="badge bg-teal text-teal-fg ms-2"><?php echo $accPending['total']; ?></span>
						  </div>
                        </a>
                      </li>
                    </ul>
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
                          <th><?php echo lang($messages, 'admin', 'request', 'table', 'wallet', 'name'); ?></th>
                          <th><?php echo lang($messages, 'admin', 'request', 'table', 'wallet', 'owner'); ?></th>
                          <th><?php echo lang($messages, 'admin', 'request', 'table', 'wallet', 'status', 'title'); ?></th>
                          <th><?php echo lang($messages, 'admin', 'request', 'table', 'wallet', 'registered'); ?></th>
                          <th class="w-1"><?php echo lang($messages, 'admin', 'request', 'table', 'wallet', 'actions'); ?></th>
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
<div class="modal modal-blur fade" id="verifyWallet" tabindex="-1" role="dialog" aria-modal="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form id="verifyTheWallet" method="POST" class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><?php echo lang($messages, 'admin', 'request', 'modal', 'wallet', 'verify', 'title'); ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
			
            <div class="row mb-3 align-items-end">
              <div class="col-auto">
                <span class="avatar avatar-upload rounded" style="background-image: url('<?php echo IMAGE_ICON; ?>'); background-size: cover; background-position: center;"></span>
              </div>
              <div class="col">
                <label class="form-label"><?php echo lang($messages, 'admin', 'request', 'modal', 'wallet', 'verify', 'name', 'label'); ?></label>
                <input type="text" class="form-control" value="<?php echo lang($messages, 'admin', 'request', 'modal', 'wallet', 'verify', 'name', 'placeholder'); ?>" id="wallet_name" name="wallet_name" readonly>
                <input type="hidden" class="form-control" value="" id="wallet_id" name="wallet_id">
              </div>
            </div>

            <div class="mb-2">
				<label class="form-label"><?php echo lang($messages, 'admin', 'request', 'modal', 'wallet', 'verify', 'balance', 'label'); ?></label>
                <input type="number" class="form-control" min="0" value="300000" placeholder="<?php echo lang($messages, 'admin', 'request', 'modal', 'wallet', 'verify', 'balance', 'placeholder'); ?>" id="wallet_bal" name="wallet_bal">
            </div>
            <div class="mb-2">
				<label class="form-label"><?php echo lang($messages, 'admin', 'request', 'modal', 'wallet', 'verify', 'account', 'label'); ?></label>
                <input type="number" class="form-control" min="0" value="20" placeholder="<?php echo lang($messages, 'admin', 'request', 'modal', 'wallet', 'verify', 'account', 'placeholder'); ?>" id="wallet_acc" name="wallet_acc">
            </div>
            <div class="mb-2">
				<label class="form-label"><?php echo lang($messages, 'admin', 'request', 'modal', 'wallet', 'verify', 'receipt', 'label'); ?></label>
                <input type="number" class="form-control" min="0" value="100" placeholder="<?php echo lang($messages, 'admin', 'request', 'modal', 'wallet', 'verify', 'receipt', 'placeholder'); ?>" id="wallet_rec" name="wallet_rec">
            </div>
            <div class="mb-2">
				<p><?php echo lang($messages, 'admin', 'request', 'modal', 'wallet', 'verify', 'description'); ?></p>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn me-auto" data-bs-dismiss="modal"><?php echo lang($messages, 'admin', 'request', 'modal', 'wallet', 'verify', 'buttons', 'cancel'); ?></button>
            <button type="submit" class="btn btn-azure"><?php echo lang($messages, 'admin', 'request', 'modal', 'wallet', 'verify', 'buttons', 'submit'); ?></button>
          </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
	
    var e = $('#total');
    var w = $('#where');
    var s = $('#search');
	
    $('#verifyTheWallet').submit(function(e) {
		e.preventDefault();
		
        var formData = $(this).serializeArray();
		formData.push({ name: 'result', value: 'verifyWallet' });
		
		var i = 0;
		var expireValue = $('#wallet_bal').val();
		if (expireValue.trim() === '') { $('#wallet_bal').addClass('is-invalid'); i++; } else { $('#wallet_bal').removeClass('is-invalid'); }
		var expireValue = $('#wallet_acc').val();
		if (expireValue.trim() === '') { $('#wallet_acc').addClass('is-invalid'); i++; } else { $('#wallet_acc').removeClass('is-invalid'); }
		var expireValue = $('#wallet_rec').val();
		if (expireValue.trim() === '') { $('#wallet_rec').addClass('is-invalid'); i++; } else { $('#wallet_rec').removeClass('is-invalid'); }
		var expireValue = $('#wallet_id').val();
		if (expireValue.trim() === '') { $('#wallet_id').addClass('is-invalid'); i++; } else { $('#wallet_id').removeClass('is-invalid'); }
		if (i > 0) { return; }
		
		$.ajax({
			url: site_domain + '/execute/action.php',
			type: 'POST',
			data: formData,
			success: function(response) {
				var jsonData = JSON.parse(response);
				if (jsonData.success == 1) {
					alertify.set('notifier','position', 'top-right');
					alertify.notify(jsonData.message, 'success', 5, function(){  console.log(jsonData.message); });
					updateAdminSQL();
					$('#verifyWallet').modal('hide');
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
	
    window.updateAdminSQL = function() {
        var pag = $('#paginationID').val();
        var total = e.val();
        var where = w.val();
        var search = s.val();
        var result = 'admin_request_wallet';

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
				
				window.checkWallet = function(dataid, name) {
					$('#wallet_name').val(name);
					$('#wallet_id').val(dataid);
					$('#verifyWallet').modal('show');
				}
				
				window.deleteWallet = function(id) {
					var result = 'deleteWallet';
					$.ajax({
						url: site_domain + '/execute/action.php',
						type: 'POST',
						data: { result: result, dataid: id },
						success: function(response) {
							var jsonData = JSON.parse(response);
							if (jsonData.success == 1) {
								alertify.set('notifier','position', 'top-right');
								alertify.notify(jsonData.message, 'success', 5, function(){  console.log(jsonData.message); });
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
				}
				
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
	} else if ($page[2] == 'accounts') {
		
?>

<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title"><?php echo lang($messages, 'admin', 'request', 'title'); ?></h2>
                <div class="page-pretitle"><?php echo lang($messages, 'admin', 'request', 'subtitle'); ?></div>
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
			
			<div class="col-12">
				<div class="card">
                  <div class="card-body">
                    <ul class="pagination ">
                      <li class="page-item page-prev">
                        <a class="page-link" href="<?php echo URI; ?>/admin/request">
                          <div class="page-item-subtitle"><?php echo lang($messages, 'admin', 'request', 'buttons', 'wallet', 'title'); ?></div>
                          <div class="page-item-title">
							<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-wallet"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" /><path d="M20 12v4h-4a2 2 0 0 1 0 -4h4" /></svg>
							<?php echo lang($messages, 'admin', 'request', 'buttons', 'wallet', 'subtitle'); ?>
							<span class="badge bg-teal text-teal-fg ms-2"><?php echo $wallPending['total']; ?></span>
						  </div>
                        </a>
                      </li>
                      <li class="page-item page-next disabled">
                        <a class="page-link" href="<?php echo URI; ?>/admin/request/accounts" tabindex="-1" aria-disabled="true">
                          <div class="page-item-subtitle"><?php echo lang($messages, 'admin', 'request', 'buttons', 'account', 'title'); ?></div>
                          <div class="page-item-title">
							<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
							<?php echo lang($messages, 'admin', 'request', 'buttons', 'account', 'subtitle'); ?>
							<span class="badge bg-teal text-teal-fg ms-2"><?php echo $accPending['total']; ?></span>
						  </div>
                        </a>
                      </li>
                    </ul>
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
                          <th><?php echo lang($messages, 'admin', 'request', 'table', 'account', 'user'); ?></th>
                          <th><?php echo lang($messages, 'admin', 'request', 'table', 'account', 'gid'); ?></th>
                          <th><?php echo lang($messages, 'admin', 'request', 'table', 'account', 'status', 'title'); ?></th>
                          <th><?php echo lang($messages, 'admin', 'request', 'table', 'account', 'registered'); ?></th>
                          <th class="w-1"><?php echo lang($messages, 'admin', 'request', 'table', 'account', 'actions'); ?></th>
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

<div class="modal modal-blur fade" id="verifyUser" tabindex="-1" role="dialog" aria-modal="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form id="verifyTheUser" method="POST" class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><?php echo lang($messages, 'admin', 'request', 'modal', 'user', 'verify', 'title'); ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
			
            <div class="row mb-3 align-items-end">
              <div class="col-auto">
                <span id="avatar" class="avatar avatar-upload rounded" style="background-image: url('<?php echo IMAGE_ICON; ?>'); background-size: cover; background-position: center;"></span>
              </div>
              <div class="col">
                <label class="form-label"><?php echo lang($messages, 'admin', 'request', 'modal', 'user', 'verify', 'name', 'label'); ?></label>
                <input type="text" class="form-control" value="<?php echo lang($messages, 'admin', 'request', 'modal', 'user', 'verify', 'name', 'placeholder'); ?>" id="wallet_name" name="wallet_name" readonly>
                <input type="hidden" class="form-control" value="" id="wallet_id" name="wallet_id">
              </div>
            </div>

            <div class="mb-2">
				<label class="form-label"><?php echo lang($messages, 'admin', 'request', 'modal', 'user', 'verify', 'wallet', 'label'); ?></label>
                <input type="number" class="form-control" min="0" value="3" placeholder="<?php echo lang($messages, 'admin', 'request', 'modal', 'user', 'verify', 'wallet', 'placeholder'); ?>" id="wallet_bal" name="wallet_bal">
            </div>
            <div class="mb-2">
				<p><?php echo lang($messages, 'admin', 'request', 'modal', 'user', 'verify', 'description'); ?></p>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn me-auto" data-bs-dismiss="modal"><?php echo lang($messages, 'admin', 'request', 'modal', 'user', 'verify', 'buttons', 'cancel'); ?></button>
            <button type="submit" class="btn btn-azure"><?php echo lang($messages, 'admin', 'request', 'modal', 'user', 'verify', 'buttons', 'submit'); ?></button>
          </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
	
    var e = $('#total');
    var w = $('#where');
    var s = $('#search');
	
    $('#verifyTheUser').submit(function(e) {
		e.preventDefault();
		
        var formData = $(this).serializeArray();
		formData.push({ name: 'result', value: 'verifyUser' });
		
		var i = 0;
		var expireValue = $('#wallet_bal').val();
		if (expireValue.trim() === '') { $('#wallet_bal').addClass('is-invalid'); i++; } else { $('#wallet_bal').removeClass('is-invalid'); }
		var expireValue = $('#wallet_id').val();
		if (expireValue.trim() === '') { $('#wallet_id').addClass('is-invalid'); i++; } else { $('#wallet_id').removeClass('is-invalid'); }
		if (i > 0) { return; }
		
		$.ajax({
			url: site_domain + '/execute/action.php',
			type: 'POST',
			data: formData,
			success: function(response) {
				var jsonData = JSON.parse(response);
				if (jsonData.success == 1) {
					alertify.set('notifier','position', 'top-right');
					alertify.notify(jsonData.message, 'success', 5, function(){  console.log(jsonData.message); });
					updateAdminSQL();
					$('#verifyUser').modal('hide');
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

    window.updateAdminSQL = function() {
        var pag = $('#paginationID').val();
        var total = e.val();
        var where = w.val();
        var search = s.val();
        var result = 'admin_request_account';

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
				
				window.checkUser = function(dataid, name, avatar) {
					$('#wallet_name').val(name);
					$('#wallet_id').val(dataid);
					$('#avatar').css('background-image', 'url(' + avatar + ')');
					$('#verifyUser').modal('show');
				}
				
				
				window.deleteUser = function(id) {
					var result = 'deleteUser';
					$.ajax({
						url: site_domain + '/execute/action.php',
						type: 'POST',
						data: { result: result, dataid: id },
						success: function(response) {
							var jsonData = JSON.parse(response);
							if (jsonData.success == 1) {
								alertify.set('notifier','position', 'top-right');
								alertify.notify(jsonData.message, 'success', 5, function(){  console.log(jsonData.message); });
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
				}
				
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
	}
} else if ($page[1] == 'wallet') {
?>
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title"><?php echo lang($messages, 'admin', 'wallet', 'title'); ?></h2>
                <div class="page-pretitle"><?php echo lang($messages, 'admin', 'wallet', 'subtitle'); ?></div>
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
                          <th><?php echo lang($messages, 'admin', 'wallet', 'table', 'name'); ?></th>
                          <th><?php echo lang($messages, 'admin', 'wallet', 'table', 'users'); ?></th>
                          <th><?php echo lang($messages, 'admin', 'wallet', 'table', 'account'); ?></th>
                          <th><?php echo lang($messages, 'admin', 'wallet', 'table', 'balance'); ?></th>
                          <th><?php echo lang($messages, 'admin', 'wallet', 'table', 'receipt'); ?></th>
                          <th><?php echo lang($messages, 'admin', 'wallet', 'table', 'status', 'title'); ?></th>
                          <th><?php echo lang($messages, 'admin', 'wallet', 'table', 'registered'); ?></th>
                          <th class="w-1"><?php echo lang($messages, 'admin', 'wallet', 'table', 'actions'); ?></th>
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

<div class="modal modal-blur fade" id="editWallet" tabindex="-1" role="dialog" aria-modal="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form id="editTheWallet" method="POST" class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><?php echo lang($messages, 'admin', 'request', 'modal', 'wallet', 'edit', 'title'); ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
			
            <div class="row mb-3 align-items-end">
              <div class="col-auto">
                <span class="avatar avatar-upload rounded" style="background-image: url('<?php echo IMAGE_ICON; ?>'); background-size: cover; background-position: center;"></span>
              </div>
              <div class="col">
                <label class="form-label"><?php echo lang($messages, 'admin', 'request', 'modal', 'wallet', 'edit', 'name', 'label'); ?></label>
                <input type="text" class="form-control" value="<?php echo lang($messages, 'admin', 'request', 'modal', 'wallet', 'edit', 'name', 'placeholder'); ?>" id="wallet_name" name="wallet_name">
                <input type="hidden" class="form-control" value="" id="wallet_id" name="wallet_id">
              </div>
            </div>

            <div class="mb-2">
				<label class="form-label"><?php echo lang($messages, 'admin', 'request', 'modal', 'wallet', 'edit', 'balance', 'label'); ?></label>
                <input type="number" class="form-control" min="0" value="300000" placeholder="<?php echo lang($messages, 'admin', 'request', 'modal', 'wallet', 'edit', 'balance', 'placeholder'); ?>" id="wallet_bal" name="wallet_bal">
            </div>
            <div class="mb-2">
				<label class="form-label"><?php echo lang($messages, 'admin', 'request', 'modal', 'wallet', 'edit', 'account', 'label'); ?></label>
                <input type="number" class="form-control" min="0" value="20" placeholder="<?php echo lang($messages, 'admin', 'request', 'modal', 'wallet', 'edit', 'account', 'placeholder'); ?>" id="wallet_acc" name="wallet_acc">
            </div>
            <div class="mb-2">
				<label class="form-label"><?php echo lang($messages, 'admin', 'request', 'modal', 'wallet', 'edit', 'receipt', 'label'); ?></label>
                <input type="number" class="form-control" min="0" value="100" placeholder="<?php echo lang($messages, 'admin', 'request', 'modal', 'wallet', 'edit', 'receipt', 'placeholder'); ?>" id="wallet_rec" name="wallet_rec">
            </div>
            <div class="mb-2">
				<p><?php echo lang($messages, 'admin', 'request', 'modal', 'wallet', 'edit', 'description'); ?></p>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn me-auto" data-bs-dismiss="modal"><?php echo lang($messages, 'admin', 'request', 'modal', 'wallet', 'edit', 'buttons', 'cancel'); ?></button>
            <button type="submit" class="btn btn-azure"><?php echo lang($messages, 'admin', 'request', 'modal', 'wallet', 'edit', 'buttons', 'submit'); ?></button>
          </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
	
    var e = $('#total');
    var w = $('#where');
    var s = $('#search');
	
    $('#editTheWallet').submit(function(e) {
		e.preventDefault();
		
        var formData = $(this).serializeArray();
		formData.push({ name: 'result', value: 'editWallet' });
		
		var i = 0;
		var expireValue = $('#wallet_name').val();
		if (expireValue.trim() === '') { $('#wallet_name').addClass('is-invalid'); i++; } else { $('#wallet_name').removeClass('is-invalid'); }
		var expireValue = $('#wallet_bal').val();
		if (expireValue.trim() === '') { $('#wallet_bal').addClass('is-invalid'); i++; } else { $('#wallet_bal').removeClass('is-invalid'); }
		var expireValue = $('#wallet_acc').val();
		if (expireValue.trim() === '') { $('#wallet_acc').addClass('is-invalid'); i++; } else { $('#wallet_acc').removeClass('is-invalid'); }
		var expireValue = $('#wallet_rec').val();
		if (expireValue.trim() === '') { $('#wallet_rec').addClass('is-invalid'); i++; } else { $('#wallet_rec').removeClass('is-invalid'); }
		var expireValue = $('#wallet_id').val();
		if (expireValue.trim() === '') { $('#wallet_id').addClass('is-invalid'); i++; } else { $('#wallet_id').removeClass('is-invalid'); }
		if (i > 0) { return; }
		
		$.ajax({
			url: site_domain + '/execute/action.php',
			type: 'POST',
			data: formData,
			success: function(response) {
				var jsonData = JSON.parse(response);
				if (jsonData.success == 1) {
					alertify.set('notifier','position', 'top-right');
					alertify.notify(jsonData.message, 'success', 5, function(){  console.log(jsonData.message); });
					updateAdminSQL();
					$('#editWallet').modal('hide');
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

    window.updateAdminSQL = function() {
        var pag = $('#paginationID').val();
        var total = e.val();
        var where = w.val();
        var search = s.val();
        var result = 'admin_wallet';

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
				
				window.checkWallet = function(dataid, name) {
					$('#wallet_name').val(name);
					$('#wallet_id').val(dataid);
					$('#editWallet').modal('show');
				}
	
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
				
				window.suspend = function(id) {
					var result = 'Suspend_wallet';
					$.ajax({
						url: site_domain + '/execute/action.php',
						type: 'POST',
						data: { result: result, dataid: id },
						success: function(response) {
							var jsonData = JSON.parse(response);
							if (jsonData.success == 1) {
								alertify.set('notifier','position', 'top-right');
								alertify.notify(jsonData.message, 'success', 5, function(){  console.log(jsonData.message); });
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
				}
				
				window.unsuspend = function(id) {
					var result = 'deSuspend_wallet';
					$.ajax({
						url: site_domain + '/execute/action.php',
						type: 'POST',
						data: { result: result, dataid: id },
						success: function(response) {
							var jsonData = JSON.parse(response);
							if (jsonData.success == 1) {
								alertify.set('notifier','position', 'top-right');
								alertify.notify(jsonData.message, 'success', 5, function(){  console.log(jsonData.message); });
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
				}
				
				window.deleteWallet = function(id) {
					var result = 'deleteWallet';
					$.ajax({
						url: site_domain + '/execute/action.php',
						type: 'POST',
						data: { result: result, dataid: id },
						success: function(response) {
							var jsonData = JSON.parse(response);
							if (jsonData.success == 1) {
								alertify.set('notifier','position', 'top-right');
								alertify.notify(jsonData.message, 'success', 5, function(){  console.log(jsonData.message); });
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
				}
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
} else if ($page[1] == 'account') {
?>

<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title"><?php echo lang($messages, 'admin', 'account', 'title'); ?></h2>
                <div class="page-pretitle"><?php echo lang($messages, 'admin', 'account', 'subtitle'); ?></div>
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
                          <th><?php echo lang($messages, 'admin', 'account', 'table', 'user'); ?></th>
                          <th><?php echo lang($messages, 'admin', 'account', 'table', 'gmail'); ?></th>
                          <th><?php echo lang($messages, 'admin', 'account', 'table', 'id'); ?></th>
                          <th><?php echo lang($messages, 'admin', 'account', 'table', 'status', 'title'); ?></th>
                          <th><?php echo lang($messages, 'admin', 'account', 'table', 'registered'); ?></th>
                          <th class="w-1"><?php echo lang($messages, 'admin', 'account', 'table', 'actions'); ?></th>
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


<div class="modal modal-blur fade" id="verifyUser" tabindex="-1" role="dialog" aria-modal="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form id="editTheUser" method="POST" class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><?php echo lang($messages, 'admin', 'request', 'modal', 'user', 'edit', 'title'); ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
			
            <div class="row mb-3 align-items-end">
              <div class="col-auto">
                <span id="avatar" class="avatar avatar-upload rounded" style="background-image: url('<?php echo IMAGE_ICON; ?>'); background-size: cover; background-position: center;"></span>
              </div>
              <div class="col">
                <label class="form-label"><?php echo lang($messages, 'admin', 'request', 'modal', 'user', 'edit', 'name', 'label'); ?></label>
                <input type="text" class="form-control" value="<?php echo lang($messages, 'admin', 'request', 'modal', 'user', 'edit', 'name', 'placeholder'); ?>" id="wallet_name" name="wallet_name">
                <input type="hidden" class="form-control" value="" id="wallet_id" name="wallet_id">
              </div>
            </div>

            <div class="mb-2">
				<label class="form-label"><?php echo lang($messages, 'admin', 'request', 'modal', 'user', 'edit', 'wallet', 'label'); ?></label>
                <input type="number" class="form-control" min="0" value="3" placeholder="<?php echo lang($messages, 'admin', 'request', 'modal', 'user', 'edit', 'wallet', 'placeholder'); ?>" id="wallet_bal" name="wallet_bal">
            </div>
            <div class="mb-2">
				<p><?php echo lang($messages, 'admin', 'request', 'modal', 'user', 'edit', 'description'); ?></p>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn me-auto" data-bs-dismiss="modal"><?php echo lang($messages, 'admin', 'request', 'modal', 'user', 'edit', 'buttons', 'cancel'); ?></button>
            <button type="submit" class="btn btn-azure"><?php echo lang($messages, 'admin', 'request', 'modal', 'user', 'edit', 'buttons', 'submit'); ?></button>
          </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
	
    var e = $('#total');
    var w = $('#where');
    var s = $('#search');
	
    $('#editTheUser').submit(function(e) {
		e.preventDefault();
		
        var formData = $(this).serializeArray();
		formData.push({ name: 'result', value: 'editUser' });
		
		var i = 0;
		var expireValue = $('#wallet_name').val();
		if (expireValue.trim() === '') { $('#wallet_name').addClass('is-invalid'); i++; } else { $('#wallet_name').removeClass('is-invalid'); }
		var expireValue = $('#wallet_bal').val();
		if (expireValue.trim() === '') { $('#wallet_bal').addClass('is-invalid'); i++; } else { $('#wallet_bal').removeClass('is-invalid'); }
		var expireValue = $('#wallet_id').val();
		if (expireValue.trim() === '') { $('#wallet_id').addClass('is-invalid'); i++; } else { $('#wallet_id').removeClass('is-invalid'); }
		if (i > 0) { return; }
		
		$.ajax({
			url: site_domain + '/execute/action.php',
			type: 'POST',
			data: formData,
			success: function(response) {
				var jsonData = JSON.parse(response);
				if (jsonData.success == 1) {
					alertify.set('notifier','position', 'top-right');
					alertify.notify(jsonData.message, 'success', 5, function(){  console.log(jsonData.message); });
					updateAdminSQL();
					$('#verifyUser').modal('hide');
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

    window.updateAdminSQL = function() {
        var pag = $('#paginationID').val();
        var total = e.val();
        var where = w.val();
        var search = s.val();
        var result = 'admin_account';

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
				
				window.checkUser = function(dataid, name, avatar) {
					$('#wallet_name').val(name);
					$('#wallet_id').val(dataid);
					$('#avatar').css('background-image', 'url(' + avatar + ')');
					$('#verifyUser').modal('show');
				}
				
				window.suspend = function(id) {
					var result = 'Suspend';
					$.ajax({
						url: site_domain + '/execute/action.php',
						type: 'POST',
						data: { result: result, dataid: id },
						success: function(response) {
							var jsonData = JSON.parse(response);
							if (jsonData.success == 1) {
								alertify.set('notifier','position', 'top-right');
								alertify.notify(jsonData.message, 'success', 5, function(){  console.log(jsonData.message); });
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
				}
				
				window.unsuspend = function(id) {
					var result = 'deSuspend';
					$.ajax({
						url: site_domain + '/execute/action.php',
						type: 'POST',
						data: { result: result, dataid: id },
						success: function(response) {
							var jsonData = JSON.parse(response);
							if (jsonData.success == 1) {
								alertify.set('notifier','position', 'top-right');
								alertify.notify(jsonData.message, 'success', 5, function(){  console.log(jsonData.message); });
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
				}
				
				window.removeAdmin = function(id) {
					var result = 'removeAdmin';
					$.ajax({
						url: site_domain + '/execute/action.php',
						type: 'POST',
						data: { result: result, dataid: id },
						success: function(response) {
							var jsonData = JSON.parse(response);
							if (jsonData.success == 1) {
								alertify.set('notifier','position', 'top-right');
								alertify.notify(jsonData.message, 'success', 5, function(){  console.log(jsonData.message); });
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
				}
				window.addAdmin = function(id) {
					var result = 'addAdmin';
					$.ajax({
						url: site_domain + '/execute/action.php',
						type: 'POST',
						data: { result: result, dataid: id },
						success: function(response) {
							var jsonData = JSON.parse(response);
							if (jsonData.success == 1) {
								alertify.set('notifier','position', 'top-right');
								alertify.notify(jsonData.message, 'success', 5, function(){  console.log(jsonData.message); });
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
				}
				
				window.deleteUser = function(id) {
					var result = 'deleteUser';
					$.ajax({
						url: site_domain + '/execute/action.php',
						type: 'POST',
						data: { result: result, dataid: id },
						success: function(response) {
							var jsonData = JSON.parse(response);
							if (jsonData.success == 1) {
								alertify.set('notifier','position', 'top-right');
								alertify.notify(jsonData.message, 'success', 5, function(){  console.log(jsonData.message); });
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
				}
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
	echo $page_not_found;
}
?>
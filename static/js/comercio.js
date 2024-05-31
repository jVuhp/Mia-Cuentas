document.addEventListener('DOMContentLoaded', function() {
	
	window.modalInfo = function(id, page, modalid, product) {

		$.ajax({
			type: "POST",
			url: site_domain + '/execute/action.php',
			data: { result: 'modals', page: page, product: product },
			success: function(response) {
				var jsonData = JSON.parse(response);
				var jsonModal = $('#' + modalid);
				$('#' + id).html(jsonData.html);
				var myModal = new bootstrap.Modal(jsonModal);
				myModal.show();
			}
		});
	}
	
	
	window.updateCat = function(type, category, where, filter, offers, design) {
		//window.history.pushState({}, '', url);
		$.ajax({
			url: site_domain + '/views/' + type + '.php',
			type: 'GET',
			data: { cat: category, where: where, filter: filter, offers: offers, design_type: design },
			success: function(response) {
				$('#content').html(response);
			}
		});
	}
	

	var dropAccounts;
	var paramsDrops = new Proxy(new URLSearchParams(window.location.search), {
	  get: function get(searchParams, prop) {
	    return searchParams.get(prop);
	  }
	});
	dropAccounts = paramsDrops.drop;
	if (dropAccounts === 'account') {
		$.ajax({
				type: "POST",
				url: site_domain + '/execute/logout.php',
				data: $(this).serialize(),
				success: function(response)
				{
					location.href = site_domain;
			   }
		});
	}
});
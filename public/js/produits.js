/////////////////
	var lien = document.getElementById('afficherProduits');
	console.log(lien);
	lien.click(function() {
		$.ajax({
			url: '/afficher-produit',
			type: 'GET',
			success: function(response) {
				$('#table-responsive').html(response);
				history.pushState({}, '', '/produit');
			},
			error: function() {
				alert("Une erreur s'est produite lors du chargement de la page des produits")
			}
		});
	});

	window.onpopstate = function(event) {
		if (location.pathname === '/produit') {
			$.ajax({
				url: '/afficher-produit',
				type: 'GET',
				success: function(response) {
					$('.table-responsive').html(response);
				},
				error: function() {
					alert("Une erreur s'est produite");
				}
			});
		} else {
			location.reload();
		}
	};

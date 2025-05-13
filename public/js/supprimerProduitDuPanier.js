$(document).ready(function () {
	
	$('.btn-delete').click(function (e) {
		e.preventDefault();
		
		let button = $(this);
		let produitSlug = button.data('slug');
		let token = button.data('token');
		let row = button.closest('tr');
		
		Swal.fire({
			title: 'Êtes-vous sûr de vouloir supprimer ce produit ?',
			text: "Ce produit sera supprimé de la facture.",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#CE1010FF',
			cancelButtonColor: '#228A0DFF',
			confirmButtonText: 'Oui, supprimer',
			cancelButtonText: 'Annuler'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: '/hospitov/public/supprimer-produit-du-panier/' + produitSlug,
					type: 'POST',
					data: {
						_method: 'DELETE',
						_token: token
					},
					success: function () {
						//suppression de la ligne
						row.css({
							'background-color': '#f8d7da',
							'text-decoration': 'ligne-through'
						});

						setTimeout(function () {
							row.fadeOut(500, function () {
								$(this).remove();
							});
						}, 1000);

							Swal.fire({
								title: 'Supprimé',
								toast: true,
								icon: 'error',
								text: 'Le produit a été supprimé avec succès',
								timer: 2000,
								position: 'top-start',
								showConfirmButton: false,
								timerProgressBar: true
							});
						},
						error: function (xhr) {
							// Swal.fire('Erreur', "La suppression a échouée ❗", 'error');
							Swal.fire('Erreur', xhr.resultJSON ?.message || "La suppression a échouée ❗", 'error');
					}
				});
			}
			});
			
		});
	});

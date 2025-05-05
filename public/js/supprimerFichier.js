$(document).ready(function () {
	
	$('.btn-delete').click(function () {
		
		let button = $(this);
		let fichierId = button.data('id');
		let token = button.data('token');
		let row = button.closest('tr');
		
		Swal.fire({
			title: 'Êtes-vous de vouloir supprimer ce fichier ?',
			text: "Ce fchier sera définitivement supprimé.",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#CE1010FF',
			cancelButtonColor: '#228A0DFF',
			confirmButtonText: 'Oui, supprimer',
			cancelButtonText: 'Annuler'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: '/hospitov/public/supprimer-fichier-examen/' + fichierId,
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
								text: 'Le fichier a été supprimé avec succès ✅',
								icon: 'success',
								timer: 2000,
								showConfirmButton: false
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

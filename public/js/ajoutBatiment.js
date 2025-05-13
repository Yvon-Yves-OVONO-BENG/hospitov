document.getElementById('batiment-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('/hospitov/public/batiment/ajouter-batiment', {
        method: 'POST',
		headers: {
			'Accept': 'application/json'
		},
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
		if (data.success) {
			Swal.fire({
				toast: true,
				icon: 'success',
				title: data.message,
				position: 'top-end',
				showConfirmButton: false,
				timer: 3000,
				timerProgressBar: true,
			});
			form.reset();
		} else {
			const errorText = data.errors ? data.errors.join('<br>') : data.message;
			Swal.fire({
				icon: 'error',
				title: 'Erreur',
				html: errorText
			});
		}
	});
});

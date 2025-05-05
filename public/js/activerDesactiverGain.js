document.addEventListener('DOMContentLoaded', function() {
    $('.custom-switch-input').change(function() {
        let gainId = $(this).data('id');
        let isChecked = $(this).is(':checked');
        
        ///je cree un nouvel objet XMLHttpRequest
			var xhr = new XMLHttpRequest();
			xhr.open('POST', '/hospitov/public/gain/activer-gain', true);
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xhr.onreadystatechange = function() 
			{
				if (xhr.readyState === 4 && xhr.status === 200) 
				{
					var response = JSON.parse(xhr.responseText);
					if (response.success) 
					{
						if (isChecked == false) {

							notif({
								msg: "<b>Gain désactivé avec succès !</b>",
								type: "error",
								position: "left",
								width: 500,
								height: 60,
								autohide: true
								});

							
						}
						else if(isChecked == true){
							notif({
								msg: "<b>Gain activé avec succès !</b>",
								type: "success",
								position: "right",
								width: 500,
								height: 60,
								autohide: true
								});
						}
					}
					else 
					{
						notif({
							msg: "<b>Erreur lors de la mise à jour du gain !</b>",
							type: "danger",
							position: "left",
							width: 500,
							height: 60,
							autohide: true
							});
					}
				}
			};
			
			//j'envoie la request avec le gain ID
			xhr.send('gain_id=' + gainId);
        
    });
});
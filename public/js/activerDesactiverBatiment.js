document.addEventListener('DOMContentLoaded', function() {
    $('.custom-switch-input').change(function() {
        let batimentId = $(this).data('id');
        let isChecked = $(this).is(':checked');
        
        ///je cree un nouvel objet XMLHttpRequest
			var xhr = new XMLHttpRequest();
			xhr.open('POST', '/hospitov/public/batiment/activer-batiment', true);
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
								msg: "<b>Bâtiment retiré du service avec succès !</b>",
								type: "error",
								position: "left",
								width: 500,
								height: 60,
								autohide: true
								});

							
						}
						else if(isChecked == true){
							notif({
								msg: "<b>Bâtiment mise en service avec succès !</b>",
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
							msg: "<b>Erreur lors de la mise à jour du batiment !</b>",
							type: "danger",
							position: "left",
							width: 500,
							height: 60,
							autohide: true
							});
					}
				}
			};
			
			//j'envoie la request avec le batiment ID
			xhr.send('batiment_id=' + batimentId);
        
    });
});
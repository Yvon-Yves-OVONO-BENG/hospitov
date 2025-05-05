document.addEventListener('DOMContentLoaded', function() {
    $('.custom-switch-input').change(function() {
        let modeleId = $(this).data('id');
        let isChecked = $(this).is(':checked');
        
        ///je cree un nouvel objet XMLHttpRequest
			var xhr = new XMLHttpRequest();
			xhr.open('POST', '/hospitov/public/modele/activer-modele', true);
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
								msg: "<b>Modèle désactivé avec succès !</b>",
								type: "error",
								position: "left",
								width: 500,
								height: 60,
								autohide: true
								});
							
						}
						else if(isChecked == true){
							

								notif({
									msg: "<b>Modèle activé avec succès !</b>",
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
							msg: "<b>Erreur lors de la mise à jour du modele !</b>",
							type: "danger",
							position: "left",
							width: 500,
							height: 60,
							autohide: true
							});
					}
				}
			};
			
			//j'envoie la request avec le modele ID
			xhr.send('modele_id=' + modeleId);
        
    });
});
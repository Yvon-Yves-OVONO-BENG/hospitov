document.addEventListener('DOMContentLoaded', function() {
    $('.custom-switch-input').change(function() {
        let retenueId = $(this).data('id');
        let isChecked = $(this).is(':checked');
        
        ///je cree un nouvel objet XMLHttpRequest
			var xhr = new XMLHttpRequest();
			xhr.open('POST', '/hospitov/public/retenue/activer-retenue', true);
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
								msg: "<b>Retenue désactivée avec succès !</b>",
								type: "error",
								position: "left",
								width: 500,
								height: 60,
								autohide: true
								});

							
						}
						else if(isChecked == true){
							notif({
								msg: "<b>Retenue activée avec succès !</b>",
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
							msg: "<b>Erreur lors de la mise à jour du retenue !</b>",
							type: "danger",
							position: "left",
							width: 500,
							height: 60,
							autohide: true
							});
					}
				}
			};
			
			//j'envoie la request avec le retenue ID
			xhr.send('retenue_id=' + retenueId);
        
    });
});
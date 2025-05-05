// je stocke les fichiers sélectionnés dans un tableau
let fileQueue = [];

// Quand l'utilisateur sélectionne des fichiers
document.getElementById('fileInput').addEventListener('change', function (event) {
	const files = Array.from(event.target.files);
	const container = document.getElementById('previewContainer');

	files.forEach(file => {
		const fileId = Date.now() + '-' + file.name;
		fileQueue.push({ id: fileId, file });

		const fileElement = document.createElement('div');
		fileElement.className = 'file-preview';
		fileElement.dataset.id = fileId;

		// j'affiche l'image ou icône PDF
		let fileThumb;
		if (file.type.startsWith('image/')) {
		fileThumb = document.createElement('img');
		fileThumb.src = URL.createObjectURL(file);
		} else if (file.type === 'application/pdf') {
		fileThumb = document.createElement('div');
		fileThumb.className = 'pdf-icon';
		fileThumb.textContent = 'PDF';
		} else {
		return; // ignore les types non pris en charge
		}

		// Informations du fichier
		const fileInfo = document.createElement('div');
		fileInfo.className = 'file-info';
		fileInfo.textContent = `${file.name} | ${(file.size / 1024).toFixed(2)} KB`;

		// Boutons d'action
		const actions = document.createElement('div');
		actions.className = 'file-actions';

		// Bouton d'envoi individuel (check)
		const sendButton = document.createElement('button');
		sendButton.textContent = '✔';
		sendButton.title = "Envoyer";
		sendButton.onclick = () => sendFile(fileId, file);

		// Bouton de suppression (poubelle)
		const deleteButton = document.createElement('button');
		deleteButton.textContent = '🗑';
		deleteButton.title = "Supprimer";
		deleteButton.onclick = () => {
		fileQueue = fileQueue.filter(f => f.id !== fileId);
		fileElement.remove();
		};

		// Ajout des boutons
		actions.appendChild(sendButton);
		actions.appendChild(deleteButton);

		// Construction du bloc fichier
		fileElement.appendChild(fileThumb);
		fileElement.appendChild(fileInfo);
		fileElement.appendChild(actions);
		container.appendChild(fileElement);
	});
});

// Envoie d’un seul fichier
function sendFile(id, file) 
{
	const formData = new FormData();
	formData.append('file', file);

	// Récupération du token CSRF
	const csrfToken = document.getElementById('csrfToken').value;

	// Récupération du slug du résultat de l'examen
	const slugResultatExamen = document.getElementById('slugResultatExamen').value;

	// Requête POST avec fetch
	fetch('/hospitov/public/envoie-un-fichier', {
		method: 'POST',
		headers: {
		'X-CSRF-TOKEN': csrfToken, // Header sécurisé
		'slugResultatExamen': slugResultatExamen // Header pour le slug
		},
		body: formData
	})
	.then(res => {
		if (res.ok) {
		alert(`Fichier ${file.name} envoyé avec succès.`);
		
		//je supprime le fichier de la liste 
		supprimerFichierDeLaListe(file.name);
		} else {
			res.text().then(text => {
				console.error('Réponse du seveur : ', text);
				alert("Erreur lors de l'envoi du fichier.");
			});
		}
	})
	.catch(() => alert("Erreur réseau."));
}

function supprimerFichierDeLaListe(nomFichier)
{
	const fichiers = document.querySelectorAll('.file-preview');
	console.log(liste);
	
	fichiers.forEach(fichier =>  {
		if (fichier.dataset.name === nomFichier) {
			fichier.remove();
		}
	});
}

// Envoie de tous les fichiers
document.getElementById('generalSendBtn').addEventListener('click', () => {
	if (fileQueue.length === 0) {
		alert("Aucun fichier à envoyer.");
		return;
	}

	const formData = new FormData();
	fileQueue.forEach(entry => {
		formData.append('files[]', entry.file);
	});

	// Récupération du token CSRF
	const csrfToken = document.getElementById('csrfToken').value;

	// Récupération du slug du résultat de l'examen
	const slugResultatExamen = document.getElementById('slugResultatExamen').value;

	// Requête POST pour envoi groupé
	fetch('/hospitov/public/envoie-plusieurs-fichiers', {
		method: 'POST',
		headers: {
		'X-CSRF-TOKEN': csrfToken, // Header sécurisé
		'slugResultatExamen': slugResultatExamen // Header pour le slug
		},
		body: formData
	})
	.then(res => {
		if (res.ok) {
		alert("Tous les fichiers ont été envoyés !");
		fileQueue = [];
		document.getElementById('previewContainer').innerHTML = '';
		} else {
			res.text().then(text => {
				console.error('Réponse du seveur : ', text);
				alert("Erreur lors de l'envoi groupé.");
			});
		
		}
	})
	.catch(() => alert("Erreur réseau."));
});

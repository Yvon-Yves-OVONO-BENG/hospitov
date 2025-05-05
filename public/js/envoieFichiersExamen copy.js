		const fileInput = document.getElementById('file-input');
        const fileListElement = document.getElementById('file-list');
        const resultatId = document.getElementById('resultat-id').value;
        let fileList = [];

        /* Quand on sélectionne des fichiers, on les stocke dans un tableau*/
        fileInput.addEventListener('change', (event) => {
            fileList = Array.from(event.target.files);
            renderFileList(); // Affiche la liste
        });

        /*Affiche les fichiers avec bouton "Envoyer" et "Supprimer"*/
        function renderFileList() {
            fileListElement.innerHTML = '';
            fileList.forEach((file, index) => {
                const li = document.createElement('li');
                li.textContent = file.name + ' ';

                // Bouton pour envoyer un fichier individuellement
                const sendBtn = document.createElement('button');
                sendBtn.textContent = 'Envoyer';
                sendBtn.addEventListener('click', () => sendFile(index));

                // Bouton pour supprimer un fichier de la liste
                const deleteBtn = document.createElement('button');
                deleteBtn.textContent = 'Supprimer';
                deleteBtn.addEventListener('click', () => {
                    fileList.splice(index, 1); // Supprime le fichier du tableau
                    renderFileList(); // Recharge la liste
                });

                li.appendChild(sendBtn);
                li.appendChild(deleteBtn);
                fileListElement.appendChild(li);
            });
        }

        // Envoie un fichier au backend (upload unitaire)
        function sendFile(index) {
            const formData = new FormData();
            formData.append('file', fileList[index]);
            formData.append('resultat_id', resultatId);

            fetch('/exam/upload-single', {
                method: 'POST',
                body: formData
            }).then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(`Fichier ${fileList[index].name} envoyé avec succès.`);
                    fileList.splice(index, 1); // Supprime le fichier envoyé de la liste
                    renderFileList();
                } else {
                    alert("Erreur lors de l'envoi du fichier.");
                }
            });
        }

        // Envoie tous les fichiers d'un coup
        document.getElementById('send-all-btn').addEventListener('click', () => {
            const formData = new FormData();
            fileList.forEach(file => formData.append('files[]', file));
            formData.append('resultat_id', resultatId);

            fetch('/exam/upload-all', {
                method: 'POST',
                body: formData
            }).then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Tous les fichiers ont été envoyés.');
                    fileList = []; // Vide la liste après envoi
                    renderFileList();
                } else {
                    alert("Erreur lors de l'envoi global.");
                }
            });
        });
$(document).ready(function () {
    // Initialisation de DataTable
    const table = $('#basicExample').DataTable();

    // Fonction pour charger les patients
    function fetchPatients(dateDebut, dateFin) {
        console.log("Appel AJAX avec :", dateDebut, dateFin);
        
        $.ajax({
            url: '/hospitov/public/patient/patient-par-periode',
            method: 'GET',
            data: {
                date_debut: dateDebut,
                date_fin: dateFin
            },
            success: function (data) {
                updateTable(data);
            },
            error: function (err) {
                // console.error('Erreur AJAX:', err);
                Swal.fire({
                icon: "error",
                title: "Erreur d'affichege",
                text: "Erreur de chargement des patients.",
				showConfirmButton: true,
            });
            }
        });
    }

    // Mise à jour du tableau
    function updateTable(patients) {
        console.log('Patients reçus', patients);
        
        table.clear(); // vide le tableau
        patients.forEach((p, i) => {
            const actionButtons = 
            `
                <div class="d-inline-flex gap-1">
                    <a href="${p.edit_url}" class="btn btn-outline-success btn-sm"
                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{% trans %}Modifier{% endtrans %} : ${p.nom}">
                        <i class="ri-edit-box-line"></i>
                    </a>
                    <a href="${p.details_url}" class="btn btn-outline-info btn-sm"
                    data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{% trans %}Afficher les détails de{% endtrans %} : ${p.nom}">
                    <i class="ri-eye-line"></i>
                    </a>
                </div>
            `
            table.row.add([
                i + 1,
                p.code,
                p.nom,
                p.genre,
                p.telephone,
                p.adresse,
                actionButtons
            ]);
        });
        table.draw(); // rafraîchit le tableau
    }

    // Événement sur le champ de date de début
    $('#date_debut').on('change', function () {
        const dateDebut = $(this).val();
        const dateFin = $('#date_fin').val();
        if (dateDebut && !dateFin) {
            fetchPatients(dateDebut, dateDebut); // un seul jour
        }
    });

    // Événement sur le champ de date de fin
    $('#date_fin').on('change', function () {
        const dateDebut = $('#date_debut').val();
        const dateFin = $(this).val();
        if (dateDebut && dateFin) {
            fetchPatients(dateDebut, dateFin);
        }
    });

    // Impression
    $('#btnImprimer').on('click', function () {
        window.print();
    });
});
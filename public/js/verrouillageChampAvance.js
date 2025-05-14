document.addEventListener('DOMContentLoaded', function () {
    const modeSelect = document.querySelector('.modePaiement');
    const avanceInput = document.querySelector('.avance');
    const montant = parseFloat(avanceInput.value);
    console.log('ok');
    function updateAvanceField() {
        const selectedText = modeSelect.options[modeSelect.selectedIndex].text.toUpperCase();

        if (selectedText === 'CASH') {
            avanceInput.readOnly = true;
        } else {
            avanceInput.readOnly = false;
        }
    }

    modeSelect.addEventListener('change', updateAvanceField);

    document.querySelector('form').addEventListener('submit', function (e) {
        const selectedText = modeSelect.options[modeSelect.selectedIndex].text.toUpperCase();

        if (selectedText === 'CRÉDIT' || selectedText === 'CREDIT') {
            const avancaVal = parseFloat(avanceInput.value);
            if (avancaVal >= montant || isNan(avanceVal)) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: "L'avance pour un crédit doit être inférieure à " + montant,
                    showConfirmButton: true,
                    confirmButtonText: "D'accord",
                });
            }
        }
    });

    updateAvanceField();
});
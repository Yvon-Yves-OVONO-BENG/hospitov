document.addEventListener('DOMContentLoaded', function () {
    let container = document.getElementById('kit_produits_container');
    let addButton = document.getElementById('add_item');

    let index = container.querySelectorAll('.kit-produit-item').length;
    let prototype = container.dataset.prototype;

    addButton.addEventListener('click', () => {
        let newForm = prototype.replace(/_name_/g, index);
        let div = document.createElement('div');
        div.classList.add('kit-produit-item');
        div.innerHTML = newForm + '<button type="button" class="btn btn-danger remove-item">Supprimer</button>';
        container.appendChild(div);
        index++;
    });

    container.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('remove-item')) {
            e.target.closest('.kit-produit-item').remove();
        }
    });
});

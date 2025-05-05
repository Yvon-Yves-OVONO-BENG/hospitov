//recherche des modeles
document.addEventListener('DOMContentLoaded', function() {

    let marque = document.getElementById("idMarque");
    marque.addEventListener("change", () => {
    const data = new FormData();
    data.append('id', marque.value);
    const requeteAjax = new XMLHttpRequest();
    let idRouteModele = document.getElementById("idModele");
    requeteAjax.open('POST', idRouteModele.getAttribute("data-path"));

    requeteAjax.onload = function () {
        const resultat = JSON.parse(requeteAjax.responseText);
        let html = '<option selected disabled value="">--Choisir le modèle--</option>'
        const options = resultat.reverse().map(function (modele) {
        return `
            <option value="${modele.id}">${modele.modele}</option>
            `
        }).join('');
        const modeles = document.getElementById("idModele");
        html += options;
        modeles.innerHTML = html;
        modeles.scrollTop = modeles.scrollHeight;
    }
    requeteAjax.send(data);
    })
})
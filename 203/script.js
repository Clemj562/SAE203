const btnAdmin = document.getElementsByClassName('btn-gestion-admin')[0];
const btnsProduit = Array.from(document.getElementsByClassName('btn-gestion-produit'));
const btnsBoutique = Array.from(document.getElementsByClassName('btn-gestion-boutique'));

function toggleButtons(buttons) {
    buttons.forEach(btn => {
        if (btn.style.display === 'none') {
            btn.style.display = 'inline-block'
        } else {
            btn.style.display = 'none'; 
        }
    });
}

btnAdmin.addEventListener('click', function () {
    toggleButtons(btnsProduit);
    toggleButtons(btnsBoutique);
});

// POPUP PRODUITS //

document.addEventListener('DOMContentLoaded', function () {
    const btnGestionBoutiques = document.querySelectorAll('.btn-gestion-produit');
    const popupBoutiques = document.getElementById('popup-boutiques');
    const btnFermerBoutiques = document.querySelector('.btn-fermer-boutiques'); 
    const popupOverlayBoutiques = document.querySelector('.popup-overlay-boutiques');
    const nomMagasinBoutiques = document.getElementById('nom-magasin-boutiques'); 

    btnGestionBoutiques.forEach(function (btn) {
        btn.addEventListener('click', function (clic) {
            clic.preventDefault();

            const magasinNom = document.querySelector('h1').textContent;
            const magasinId = document.querySelector('.btn-gestion-stock').dataset.magasinId;

            nomMagasinBoutiques.textContent = magasinNom;
            if (magasinId) {
                nomMagasinBoutiques.dataset.magasinId = magasinId;
            }
            popupBoutiques.classList.remove('popup-hide');
            popupBoutiques.classList.add('popup-show-produit');
        });
    });

    function fermerPopupBoutiques() {
        popupBoutiques.classList.remove('popup-show-produit');
        popupBoutiques.classList.add('popup-hide');
        document.body.style.overflow = '';
    }

    if (btnFermerBoutiques) { 
        btnFermerBoutiques.addEventListener('click', fermerPopupBoutiques);
    }
    if (popupOverlayBoutiques) {
        popupOverlayBoutiques.addEventListener('click', fermerPopupBoutiques);
    }
});
// POPUP BOUTIQUES //

document.addEventListener('DOMContentLoaded', function () {
    const btnGestionBoutiques = document.querySelectorAll('.btn-gestion-boutique');
    const popupBoutiques = document.getElementById('popup-boutiques');
    const btnFermerBoutiques = document.querySelector('.popup-header-boutiques .btn-fermer-boutiques');
    const popupOverlayBoutiques = document.querySelector('.popup-overlay-boutiques');
    const nomMagasinBoutiques = document.getElementById('nom-magasin-boutiques');

    btnGestionBoutiques.forEach(function (btn) {
        btn.addEventListener('click', function (clic) {
            clic.preventDefault();

            const magasinNom = document.querySelector('h1')
            magasinNom.textContent;
            const magasinId = document.querySelector('.btn-gestion-stock');

            nomMagasinBoutiques.textContent = magasinNom;
            if (magasinId) {
                nomMagasinBoutiques.dataset.magasinId = magasinId;
            }
            popupBoutiques.classList.remove('popup-hide');
            popupBoutiques.classList.add('popup-show-boutiques');

        });
    });

    function fermerPopupBoutiques() {
        popupBoutiques.classList.remove('popup-show-boutiques');
        popupBoutiques.classList.add('popup-hide');
        document.body.style.overflow = '';
    }

    btnFermerBoutiques.addEventListener('click', fermerPopupBoutiques);
    popupOverlayBoutiques.addEventListener('click', fermerPopupBoutiques);
});

// POPUP GESTION DES STOCKS // (incliquable pour le gérant)

document.addEventListener('DOMContentLoaded', function () {
    const btnGestionStock = document.querySelector('.btn-gestion-stock');
    const popupStock = document.getElementById('popup-stock');
    const btnFermerStock = document.querySelector('.popup-header-stock .btn-fermer-stock');
    const popupOverlayStock = document.querySelector('.popup-overlay-stock');
    const nomMagasinStock = document.getElementById('nom-magasin-stock');

    btnGestionStock.addEventListener('click', function (clic) {
        clic.preventDefault();
        const magasinNom = this.dataset.magasin;
        nomMagasinStock.textContent = magasinNom;
        popupStock.classList.remove('popup-hide');
        popupStock.classList.add('popup-show-stock');
    });

    function fermerPopupStock() {
        popupStock.classList.remove('popup-show-stock');
        popupStock.classList.add('popup-hide');
    }

    btnFermerStock.addEventListener('click', fermerPopupStock);
    popupOverlayStock.addEventListener('click', fermerPopupStock);


    const boutons = document.querySelectorAll('.btn-diminuer, .btn-augmenter');
    boutons.forEach(btn => {
        btn.addEventListener('click', function () {
            const elementStock = this.closest('.item-stock');
            const input = elementStock.querySelector('.quantite-input');
            const action = this.dataset.action;
            let value = parseInt(input.value) || 0;

            if (action === 'augmenter') {
                value++;
            } else if (action === 'diminuer' && value > 0) {
                value--;
            }

            input.value = value;
        });
    });

    document.querySelectorAll('.quantite-input').forEach(input => {
        input.addEventListener('input', function () {
            let valeur = parseInt(this.value);
            if (isNaN(valeur) || valeur < 0) {
                this.value = 0;
            }
        });
    });

    const sauvegarde = document.querySelectorAll('.btn-sauvegarder');
    sauvegarde.forEach(btn => {
        btn.addEventListener('click', function () {
            const stockId = this.dataset.stockId;
            const elementStock = this.closest('.item-stock');
            const nouvelleQuantite = elementStock.querySelector('.quantite-input').value;

            this.classList.add('saving');
            this.textContent = 'Sauvegarde...';
            this.disabled = true;

        });
    });

});

// BOUTON RETOUR //

document.getElementById("lien-page-retour").addEventListener("click", () => {
    history.back();
});


// CHANGEMENT BOUTIQUE SELECT //

document.getElementById('boutique-select').addEventListener('change', function () {
    if (this.value) {
        window.location.href = 'boutique.php?id-boutique=' + this.value;
    }
});




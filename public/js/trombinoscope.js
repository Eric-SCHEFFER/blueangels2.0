// Fenêtre modale pour afficher en grand le détail des infos d'un membre cliqué sur une carte du trombinoscope

// La variable oBody existe déjà depuis le script navbar.js
const cards = document.querySelectorAll(".card");
const modal = document.querySelector("#modal");
const modalContent = modal.firstElementChild;
const close = modalContent.firstElementChild;


for (let card of cards) {
   card.addEventListener("click", function (event) {
      event.stopPropagation();
      datas = this.querySelector(".datas");
      // On ajoute du contenu

      champNom = modalContent.querySelector('.nom');
      champNom.innerHTML = datas.dataset.prenom;

      modal.classList.add("show");
   });
}

// Ferme la modale quand on clique n'importe où
oBody.addEventListener("click", function () {
   modal.classList.remove("show");
});

modalContent.addEventListener("click", function (event) {
   event.stopPropagation();
});

// Ferme la modale quand on clique sur son bouton fermer
close.addEventListener("click", function () {
   modal.classList.remove("show");
});
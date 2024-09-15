// Fenêtre modale pour afficher en grand le détail des infos d'un membre cliqué sur une carte du trombinoscope

// La variable oBody existe déjà depuis le script navbar.js
const cards = document.querySelectorAll(".card");
const modal = document.querySelector("#modal");
const modalContent = modal.firstElementChild;
const close = modalContent.firstElementChild;
const darkerLayer = document.querySelector(".darker-layer");

for (let card of cards) {
   card.addEventListener("click", function (event) {
      event.stopPropagation();
      datas = this.querySelector(".datas");
      // On ajoute du contenu
      modalContent.querySelector('.prenom').innerText = datas.dataset.prenom;
      modalContent.querySelector('.nom').innerText = datas.dataset.nom;
      modalContent.querySelector('.fonction').innerText = datas.dataset.fonction;
      modalContent.querySelector('.description').innerText = datas.dataset.description;
      modalContent.querySelector('.telephone').innerText = datas.dataset.telephone;
      modalContent.querySelector('.email').innerText = datas.dataset.email;
      modalContent.querySelector('.facebook').innerText = datas.dataset.facebook;
      modalContent.querySelector('.hook img').src = card.querySelector("img").src;
      // On affiche la modale
      modal.classList.add("show", "restore-click", "restore-scroll");
      darkerLayer.classList.add("to-dark");
      oBody.classList.add("no-click-no-scroll");
   });
}

// Ferme la modale quand on clique n'importe où
oBody.addEventListener("click", function () {
   modal.classList.remove("show", "restore-click", "restore-scroll");
   darkerLayer.classList.remove("to-dark");
   oBody.classList.remove("no-click-no-scroll");
});

modalContent.addEventListener("click", function (event) {
   event.stopPropagation();
});

// Ferme la modale quand on clique sur son bouton fermer
close.addEventListener("click", function () {
   modal.classList.remove("show", "restore-click", "restore-scroll");
   darkerLayer.classList.remove("to-dark");
   oBody.classList.remove("no-click-no-scroll");
});

// Fermeture de la modale avec echap
document.addEventListener("keyup", function (event) {
   if (modal.classList.contains("show")) {
      if (event.key == "Escape") {
         modal.classList.remove("show", "restore-click", "restore-scroll");
         darkerLayer.classList.remove("to-dark");
         oBody.classList.remove("no-click-no-scroll");
      }
   }
});


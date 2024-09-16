// Fenêtre modale pour afficher en grand le détail des infos d'un membre cliqué sur une carte du trombinoscope

// La variable oBody existe déjà depuis le script navbar.js
const cards = document.querySelectorAll(".card");
const modal = document.querySelector("#modal");
const modalContent = modal.firstElementChild;
const close = modalContent.firstElementChild;
const infosMembre = document.querySelector(".infos-membre");
// const dtTags = infosMembre.querySelectorAll("dt");
// const ddTags = infosMembre.querySelectorAll("dd");
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

      // TODO: On ajoute les balises dl,dt et dd, et leurs contenus avec icônes fontawesome (dans div class infos-membre)
      if (datas.dataset.telephone !== "") {
         let dlTelephone = document.createElement("dl");
         dlTelephone.classList.add("telephone");
         let dtTelephone = document.createElement("dt");
         let ddTelephone = document.createElement("dd");
         let iTelephone = document.createElement("i");
         iTelephone.classList.add("fa-solid", "fa-phone")
         infosMembre.appendChild(dtTelephone);
         infosMembre.appendChild(ddTelephone);
         dtTelephone.appendChild(iTelephone);
         ddTelephone.innerText = datas.dataset.telephone;
      }
      if (datas.dataset.email !== "") {
         let dlEmail = document.createElement("dl");
         dlEmail.classList.add("email");
         let dtEmail = document.createElement("dt");
         let ddEmail = document.createElement("dd");
         let iEmail = document.createElement("i");
         iEmail.classList.add("fa-solid", "fa-envelope")
         infosMembre.appendChild(dtEmail);
         infosMembre.appendChild(ddEmail);
         dtEmail.appendChild(iEmail);
         ddEmail.innerText = datas.dataset.email;
      }
      if (datas.dataset.facebook !== "") {
         let dlFacebook = document.createElement("dl");
         dlFacebook.classList.add("telephone");
         let dtFacebook = document.createElement("dt");
         let ddFacebook = document.createElement("dd");
         let iFacebook = document.createElement("i");
         iFacebook.classList.add("fa-brands", "fa-facebook-f")
         infosMembre.appendChild(dtFacebook);
         infosMembre.appendChild(ddFacebook);
         dtFacebook.appendChild(iFacebook);
         ddFacebook.innerText = datas.dataset.facebook;
      }

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
   infosMembre.innerHTML = "";
});

modalContent.addEventListener("click", function (event) {
   event.stopPropagation();
});

// Ferme la modale quand on clique sur son bouton fermer
close.addEventListener("click", function () {
   modal.classList.remove("show", "restore-click", "restore-scroll");
   darkerLayer.classList.remove("to-dark");
   oBody.classList.remove("no-click-no-scroll");
   infosMembre.innerHTML = "";
});

// Fermeture de la modale avec echap
document.addEventListener("keyup", function (event) {
   if (modal.classList.contains("show")) {
      if (event.key == "Escape") {
         modal.classList.remove("show", "restore-click", "restore-scroll");
         darkerLayer.classList.remove("to-dark");
         oBody.classList.remove("no-click-no-scroll");
         infosMembre.innerHTML = "";
      }
   }
});


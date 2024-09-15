// Fenêtre modale pour afficher en grand le détail des infos d'un membre cliqué sur une carte du trombinoscope

// La variable oBody existe déjà depuis le script navbar.js
const cards = document.querySelectorAll(".card");
const modal = document.querySelector("#modal");
const modalContent = modal.firstElementChild;
const close = modalContent.firstElementChild;
// const infosMembre = document.querySelector(".infos-membre");
const dtTags = document.querySelector(".infos-membre").querySelectorAll("dt");
const ddTags = document.querySelector(".infos-membre").querySelectorAll("dd");
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

      // Contenu avec icônes fontawesome (dans div class infos-membre)
      if (datas.dataset.telephone !== "") {
         let iTelephone = document.createElement("i");
         iTelephone.classList.add("fa-solid", "fa-phone")
         modalContent.querySelector('.telephone>dt').appendChild(iTelephone);
         modalContent.querySelector('.telephone>dd').innerText = datas.dataset.telephone;
      }
      if (datas.dataset.email !== "") {
         let iEmail = document.createElement("i");
         iEmail.classList.add("fa-solid", "fa-envelope")
         modalContent.querySelector('.email>dt').appendChild(iEmail);
         modalContent.querySelector('.email>dd').innerText = datas.dataset.email;
      }
      if (datas.dataset.facebook !== "") {
         let iFacebook = document.createElement("i");
         iFacebook.classList.add("fa-brands", "fa-facebook-f")
         modalContent.querySelector('.facebook>dt').appendChild(iFacebook);
         modalContent.querySelector('.facebook>dd').innerText = datas.dataset.facebook;
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
   for (let dtTag of dtTags) {
      dtTag.innerHTML = "";
   }
   for (let ddTag of ddTags) {
      ddTag.innerHTML = "";
   }
});

modalContent.addEventListener("click", function (event) {
   event.stopPropagation();
});

// Ferme la modale quand on clique sur son bouton fermer
close.addEventListener("click", function () {
   modal.classList.remove("show", "restore-click", "restore-scroll");
   darkerLayer.classList.remove("to-dark");
   oBody.classList.remove("no-click-no-scroll");
   for (let dtTag of dtTags) {
      dtTag.innerHTML = "";
   }
   for (let ddTag of ddTags) {
      ddTag.innerHTML = "";
   }
});

// Fermeture de la modale avec echap
document.addEventListener("keyup", function (event) {
   if (modal.classList.contains("show")) {
      if (event.key == "Escape") {
         modal.classList.remove("show", "restore-click", "restore-scroll");
         darkerLayer.classList.remove("to-dark");
         oBody.classList.remove("no-click-no-scroll");
         for (let dtTag of dtTags) {
            dtTag.innerHTML = "";
         }
         for (let ddTag of ddTags) {
            ddTag.innerHTML = "";
         }
      }
   }
});


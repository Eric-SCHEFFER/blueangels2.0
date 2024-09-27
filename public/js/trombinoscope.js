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

      // On rempli la div class "infos-membre" (Icônes fonawesome, datas, etc...)
      // Balise dl telephone
      if (datas.dataset.telephone !== "") {
         let dlTelephone = document.createElement("dl");
         dlTelephone.classList.add("telephone");
         let dtTelephone = document.createElement("dt");
         let ddTelephone = document.createElement("dd");
         let iTelephone = document.createElement("i");
         iTelephone.classList.add("fa-solid", "fa-phone");
         infosMembre.appendChild(dlTelephone);
         dlTelephone.append(dtTelephone, ddTelephone);
         dtTelephone.appendChild(iTelephone);
         ddTelephone.innerText = datas.dataset.telephone;
      }
      // Balise dl email
      if (datas.dataset.email !== "") {
         let dlEmail = document.createElement("dl");
         dlEmail.classList.add("email");
         let dtEmail = document.createElement("dt");
         let ddEmail = document.createElement("dd");
         let aEmail = document.createElement("a");
         aEmail.href = "mailto:" + datas.dataset.email;
         aEmail.title = "mailto:" + datas.dataset.email;
         let iEmail = document.createElement("i");
         iEmail.classList.add("fa-solid", "fa-envelope");
         infosMembre.appendChild(dlEmail);
         dlEmail.append(dtEmail, ddEmail);
         dtEmail.appendChild(iEmail);
         ddEmail.appendChild(aEmail);
         aEmail.innerText = datas.dataset.email;
      }
      // Balise dl facebook
      if (datas.dataset.facebook !== "") {
         let dlFacebook = document.createElement("dl");
         dlFacebook.classList.add("telephone");
         let dtFacebook = document.createElement("dt");
         let ddFacebook = document.createElement("dd");
         let aFacebook = document.createElement("a");
         aFacebook.href = datas.dataset.facebook;
         aFacebook.title = datas.dataset.facebook;
         let iFacebook = document.createElement("i");
         iFacebook.classList.add("fa-brands", "fa-facebook-f");
         infosMembre.appendChild(dlFacebook);
         dlFacebook.append(dtFacebook, ddFacebook);
         dtFacebook.appendChild(iFacebook);
         ddFacebook.appendChild(aFacebook);
         aFacebook.innerText = datas.dataset.facebook;
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
   infosMembre.innerHTML = ""; // On supprime le contenu de div class "infos-membre"
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


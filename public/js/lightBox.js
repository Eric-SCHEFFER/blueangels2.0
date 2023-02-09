// Fenêtre modale (lightbox) pour afficher l'image en grand

// La variable oBody existe déjà depuis le script navbar.js
const boutonsFullScreen = document.querySelectorAll(".full-screen");
const modale = document.querySelectorAll(".image>.modal");


// Quand il n'y a qu'une seule image dans la page

if (modale.length == 1) {
   const boutonClose = document.querySelector(".image>.modal>.close");
   boutonsFullScreen[0].addEventListener("click", function () {
      modale[0].classList.add("show", "restore-click", "restore-scroll");
      oBody.classList.add("no-click-no-scroll");
   });
   boutonClose.addEventListener("click", function () {
      modale[0].classList.remove("show", "restore-click", "restore-scroll");
      oBody.classList.remove("no-click-no-scroll");
   });
   // Fermeture de la modale avec echap
   document.addEventListener("keyup", function (event) {
      if (modale[0].classList.contains("show")) {
         if (event.key == "Escape") {
            modale[0].classList.remove("show", "restore-click", "restore-scroll");
            oBody.classList.remove("no-click-no-scroll");
         }
      }
   });
}


// Quand il y a plusieurs images

else {
   const boutonsClose = document.querySelectorAll(".close");

   for (let boutonFullScreen of boutonsFullScreen) {
      boutonFullScreen.addEventListener("click", function () {
         let modale = document.querySelector(".image.visibility-image-on:not(.display-image-off)>.modal");
         modale.classList.add("show", "restore-click", "restore-scroll");
         oBody.classList.add("no-click-no-scroll");
      });
   }

   for (let boutonClose of boutonsClose) {
      boutonClose.addEventListener("click", function () {
         let modale = boutonClose.parentElement;
         modale.classList.remove("show", "restore-click", "restore-scroll");
         oBody.classList.remove("no-click-no-scroll");
      });
      // Fermeture de la modale avec echap
      let modale = boutonClose.parentElement;
      document.addEventListener("keyup", function (event) {
         if (modale.classList.contains("show")) {
            if (event.key == "Escape") {
               modale.classList.remove("show", "restore-click", "restore-scroll");
               oBody.classList.remove("no-click-no-scroll");
            }
         }
      });
   }
}



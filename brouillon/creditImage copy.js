// Fenêtre popup pour afficher les infos de crédit de l'image

const boutonCredit = document.querySelector(".credit-image");
const creditImageContents = document.querySelectorAll(".credit-image-content");

// Une seule image

if (creditImageContents.length == 1) {
   // Ouvre la fenêtre crédit quand on clique sur son bouton
   boutonCredit.addEventListener("click", function (event) {
      event.stopPropagation();
      creditImageContents[0].classList.toggle("show-credit-image");
   });

   // Ferme la fenêtre crédit quand on clique n'importe où
   oBody.addEventListener("click", function () {
      creditImageContents[0].classList.remove("show-credit-image");
   });

   creditImageContents[0].addEventListener("click", function (event) {
      // Préserve la fermeture de la fenêtre crédit quand on clique dessus.
      event.stopPropagation();
   });

   // Ferme la fenêtre crédit quand on appuie sur son bouton close
   let close = creditImageContents[0].firstElementChild;
   close.addEventListener("click", function () {
      creditImageContents[0].classList.remove("show-credit-image");
   });
}

// Plusieurs images

else {
   // Ouvre la fenêtre crédit quand on clique sur son bouton
   boutonCredit.addEventListener("click", function (event) {
      let creditImageContent = document.querySelector(".image.visibility-image-on:not(.display-image-off)>.credit-image-content");
      creditImageContent.classList.toggle("show-credit-image");
      event.stopPropagation();
   });

   // Ferme la fenêtre crédit quand on clique n'importe où
   oBody.addEventListener("click", function () {
      let creditImageContent = document.querySelector(".image.visibility-image-on:not(.display-image-off)>.credit-image-content");
      creditImageContent.classList.remove("show-credit-image");
   });

   for (let creditImageContent of creditImageContents) {
      creditImageContent.addEventListener("click", function (event) {
         // Préserve la fermeture de la fenêtre crédit quand on clique dessus.
         event.stopPropagation();
      });

      // Ferme la fenêtre crédit quand on appuie sur son bouton close
      let close = creditImageContent.firstElementChild;
      close.addEventListener("click", function () {
         creditImageContent.classList.remove("show-credit-image");
      });
   }
}


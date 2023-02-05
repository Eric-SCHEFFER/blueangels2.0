const boutonCredit = document.querySelector(".credit-image");
const creditImageContents = document.querySelectorAll(".credit-image-content");


boutonCredit.addEventListener("click", function (event) {
   let creditImageContent = document.querySelector(".image.visibility-image-on:not(.display-image-off)>.credit-image-content");
   creditImageContent.classList.toggle("show");
   event.stopPropagation();
});

oBody.addEventListener("click", function () {
   let creditImageContent = document.querySelector(".image.visibility-image-on:not(.display-image-off)>.credit-image-content");
   creditImageContent.classList.remove("show");
});


for (let creditImageContent of creditImageContents) {
   creditImageContent.addEventListener("click", function (event) {
      // Préserve la fermeture de la fenêtre crédit quand on clique dessus.
      event.stopPropagation();
   });
   // Ferme la fenêtre crédit quand on appuie sur son bouton close
   let close = creditImageContent.firstElementChild;
   close.addEventListener("click", function () {
      creditImageContent.classList.remove("show");
   });
}

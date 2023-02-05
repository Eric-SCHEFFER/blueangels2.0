const boutonCredit = document.querySelector(".credit-image");


boutonCredit.addEventListener("click", function (event) {
   let creditImageContent = document.querySelector(".image.visibility-image-on:not(.display-image-off)>.credit-image-content");
   creditImageContent.classList.toggle("show");
   event.stopPropagation();
});

oBody.addEventListener("click", function () {
   let creditImageContent = document.querySelector(".image.visibility-image-on:not(.display-image-off)>.credit-image-content");
   creditImageContent.classList.remove("show");
});


// Préserve la fermeture de la fenetre crédit quand on clique dessus.
const creditImageContents = document.querySelectorAll(".credit-image-content");
for (let creditImageContent of creditImageContents) {
   creditImageContent.addEventListener("click", function (event) {
      event.stopPropagation();
   });
}

window.onload = () => {
   const boutonsClose = document.querySelectorAll(".close");
   const boutonFullScreen = document.querySelector(".full-screen");


   boutonFullScreen.addEventListener("click", clicBoutonFullScreen);
   function clicBoutonFullScreen() {
      let modale = document.querySelector(".image.visibility-image-on:not(.display-image-off)>.modal");
      modale.classList.add("show");
   }

   for (let boutonClose of boutonsClose) {
      boutonClose.addEventListener("click", function () {
         let modale = boutonClose.parentElement;
         modale.classList.remove("show");
      });

   }
}


window.onload = () => {
   // Fenêtre modale pour afficher l'image en grand

   const boutonFullScreen = document.querySelector(".full-screen");
   let modale = document.querySelectorAll(".image>.modal");

   // Quand il n'y a qu'une seule image dans la page
   if (modale.length == 1) {
      const boutonClose = document.querySelector(".image>.modal>.close");
      boutonFullScreen.addEventListener("click", function () {
         modale[0].classList.add("show");
      });
      boutonClose.addEventListener("click", function () {
         modale[0].classList.remove("show");
      });
   }
   // Quand il y a plusieurs images
   else {
      const boutonsClose = document.querySelectorAll(".close");
      boutonFullScreen.addEventListener("click", function () {
         let modale = document.querySelector(".image.visibility-image-on:not(.display-image-off)>.modal");
         modale.classList.add("show");
      });

      for (let boutonClose of boutonsClose) {
         boutonClose.addEventListener("click", function () {
            let modale = boutonClose.parentElement;
            modale.classList.remove("show");
         });
      }
   }
}


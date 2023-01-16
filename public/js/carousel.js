let bullet = document.getElementsByClassName('bullet');
if (bullet.length > 0) {
   let slideIndex = 1;
   showSlides(slideIndex);

   // Next/previous controls
   function plusSlides(n) {
      showSlides(slideIndex += n);
   }

   // Thumbnail image controls
   function currentSlide(n) {
      showSlides(slideIndex = n);
   }

   function showSlides(n) {
      let i;
      let slides = document.getElementsByClassName("image");
      let bullets = document.getElementsByClassName("bullet");
      let imagesCaption = document.getElementsByClassName("image-caption");
      if (n > slides.length) { slideIndex = 1 }
      if (n < 1) { slideIndex = slides.length }
      for (i = 0; i < slides.length; i++) {
         // Transitions images et caption
         // Ne fonctionne pour l'instant qu'à l'apparition
         slides[i].classList.add("display-image-off");
         
         // caption
         if (typeof imagesCaption[i] !== 'undefined') {
            imagesCaption[i].classList.add("visibility-caption-on");
         }
         
         bullets[i].className = bullets[i].className.replace(" actif", "");
      }
      
      // image
      slides[slideIndex - 1].classList.remove("display-image-off");
      slides[slideIndex - 1].classList.add("visibility-image-on");

      bullets[slideIndex - 1].className += " actif";
   }
}
else {
   if (bullet.length == 0) {
      // Affichage du caption quand il n'y a qu'une seule image
      let imagesCaption = document.getElementsByClassName("image-caption");
      if (typeof imagesCaption[0] !== 'undefined') {
         imagesCaption[0].classList.add("visibility-caption-on");
      }
   }
}





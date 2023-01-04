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
      if (n > slides.length) { slideIndex = 1 }
      if (n < 1) { slideIndex = slides.length }
      for (i = 0; i < slides.length; i++) {

         // TODO: Transitions pour l'image
         // Ne fonctionne pour l'instant qu'à l'apparition


         slides[i].classList.add("display-image-off");
         slides[i].classList.add("visibility-image-off");
         slides[i].classList.remove("display-image-on");
         slides[i].classList.remove("visibility-image-on");

      }
      for (i = 0; i < bullets.length; i++) {
         bullets[i].className = bullets[i].className.replace(" actif", "");
      }
      // TODO: Transitions pour l'image

      slides[slideIndex - 1].classList.remove("display-image-off");
      slides[slideIndex - 1].classList.add("visibility-image-on");
      slides[slideIndex - 1].classList.add("display-image-on");
      slides[slideIndex - 1].classList.remove("visibility-image-off");

      bullets[slideIndex - 1].className += " actif";
   }
}






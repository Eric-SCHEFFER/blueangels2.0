window.onload = () => {
   let caseACocher = document.getElementById("accordDonneesPerso");
   let boutonSubmit = document.getElementById("contact_envoyer");
   let messageCocherRgpd = document.getElementById("cocherRgpdErrorId");
   let contactForm = document.getElementsByName("contact");

   caseACocher.checked = false;
   // boutonSubmit.disabled = true;

   // Bascule: Active/désactive le bouton submit selon que la case d'acceptation de collecte des données soit cochée ou non.
   caseACocher.addEventListener('change', function () {
      if (this.checked) {
         boutonSubmit.className = "buttonEnabled";
         if (messageCocherRgpd.classList.contains("displayBlock")) {
            messageCocherRgpd.classList.remove("displayBlock");
         }

      } else {
         boutonSubmit.className = "buttonDisabled";
      }
   });

   // Quand On clique sur le bouton envoyer
   boutonSubmit.addEventListener('click', function (e) {
      // Quand il est disabled, on affiche un message rouge disant qu'il faut d'abord cocher la case
      if (this.classList.contains("buttonDisabled")) {
         e.preventDefault();
         messageCocherRgpd.classList.add("displayBlock");
         e.stopPropagation();
      }
      // On envoie le timeStamp dans le champ caché
      document.getElementById('contact_sendTimeClientSide').value = Math.round(Date.now() / 1000);
   });

   // On détecte un changement dans le formulaire (début d'écriture, cochage de case)
   // TODO: Ne détecter que le 1er changement
   contactForm[0].addEventListener('change', function () {
      // On envoie le timeStamp dans le champ caché
      document.getElementById('contact_beginTimeClientSide').value = Math.round(Date.now() / 1000);
   },
      { once: true }
   );
}

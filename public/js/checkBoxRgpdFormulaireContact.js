window.onload = () => {
   let caseACocher = document.getElementById("accordDonneesPerso");
   let boutonSubmit = document.getElementById("contact_envoyer");
   let messageCocherRgpd = document.getElementById("cocherRgpdErrorId");

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

   // Quand On clique sur le bouton envoyer, et qu'il est disabled, on affiche un message rouge disant qu'il faut d'abord cocher la case
   boutonSubmit.addEventListener('click', function (e) {
      if (this.classList.contains("buttonDisabled")) {
         e.preventDefault();
         messageCocherRgpd.classList.add("displayBlock");
         e.stopPropagation
      }
   });
}
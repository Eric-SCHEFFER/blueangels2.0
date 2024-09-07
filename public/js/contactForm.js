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

   // Quand On clique sur le bouton envoyer
   boutonSubmit.addEventListener('click', function (e) {
      // Quand il est disabled, on affiche un message rouge disant qu'il faut d'abord cocher la case
      if (this.classList.contains("buttonDisabled")) {
         e.preventDefault();
         messageCocherRgpd.classList.add("displayBlock");
         e.stopPropagation
      }
   });


   // TODO: Détecter le premier event d'input sur le formulaire, noter le timeStamp dans une variable.
   // Détecter l'event du déclenchement du bouton envoyer, noter le timeStamp dans une autre variable, et renvoyer ces 2 valeurs via des champs cachés, sur le serveur pour traitement dans ContactController.

   // alert(Date.now());

   document.getElementById('contact_beginTimeClientSide').value = Date.now();


}
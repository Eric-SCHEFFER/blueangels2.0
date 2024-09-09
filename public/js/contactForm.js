window.onload = () => {
   let caseACocher = document.getElementById("accordDonneesPerso");
   let boutonSubmit = document.getElementById("contact_envoyer");
   let messageCocherRgpd = document.getElementById("cocherRgpdErrorId");
   let contactForm = document.getElementsByName("contact");
   let datas = document.querySelector("#datas");
   let isFormValid = datas.dataset.dataformvalid; // Variable de twig passée par l'attribut de données data-*
   const lignesInputs = document.querySelectorAll(".constraints-messages");
   // console.log(lignesInputs);

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
      // Antispam robots: On envoie le timeStamp de la date d'envoi du formulaire dans le champ caché
      document.getElementById('contact_sendTimeClientSide').value = Math.round(Date.now() / 1000);
   });


   if (isFormValid) {
      // Premier affichage du formulaire, donc, pas d'erreurs
      // Antispam robots: On détecte l'event du premier changement dans le formulaire (début d'écriture, cochage de case)
      contactForm[0].addEventListener('change', function () {
         // On envoie le timeStamp de l'event dans le champ caché
         document.getElementById('contact_beginTimeClientSide').value = Math.round(Date.now() / 1000);
      },
         { once: true }
      );
   } else {
      // Retours d'affichage du formulaire, avec au moins 1 erreur. Les champs restent remplis.
      // On envoie dès le chargement de la page, le timeStamp de l'event dans le champ caché.
      // Explications:
      // Quand on est sur un retour d'erreur du formulaire, comme les champs sont déjà remplis,
      // pour éviter le risque d'une soumission trop rapide, si par ex 1 seul champ est en erreur,
      // le décompte du temps de soumission risque de ne commencer qu'après le cochage de la case du RGPD,
      // si, après avoir réinscrit les bonnes données dans le champ concerné, l'utilisateur clique de suite
      // sur la case rgpd, et sur envoi très rapidement (on peut le faire en moins d'1s).
      // La détection du temps de soumission côté client riquerait de faire un faux positif
      document.getElementById('contact_beginTimeClientSide').value = Math.round(Date.now() / 1000);

      // Dès qu'on commence à saisir (dans input ou textArea), on efface le message d'erreur éventuellement présent en dessous, lors d'un retour de formulaire
      // On cible chaque ligne
      for (let ligneInputs of lignesInputs) {
         const divs = ligneInputs.querySelectorAll("div");
         // On cible chaque div dans la ligne
         for (const div of divs) {
            // Si présente, on cible l'ul dans la div, et on créé un écouteur d'event sur la saisie du champ (input ou textarea)
            const ul = div.querySelector("ul");
            if (ul) {
               ul.nextElementSibling.addEventListener('beforeinput', function () {
                  // On supprime l'ul (c'est elle qui contenait la li du message d'erreur)
                  ul.remove();
               },
                  { once: true }
               );
            }
         }
      }
   }


}

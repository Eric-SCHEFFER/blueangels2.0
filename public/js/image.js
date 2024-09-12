window.onload = () => {
   // Gestion du lien "Supprimer"

   let link = document.querySelector("[data-delete]")
   // On écoute le clic
   link.addEventListener("click", function (e) {
      // On désactive le lien
      e.preventDefault()
      // On demande confirmation
      if (confirm("Voulez-vous supprimer cette image ?")) {
         // On envoi envoie une requette Ajax vers le href du lien avec la méthode DELETE
         fetch(this.getAttribute("href"), {
            method: "DELETE",
            headers: {
               "X-Requsted-With": "XMLHttpRequest",
               "Content-Type": "application/json"
            },
            body: JSON.stringify({ "_token": this.dataset.token })
         }).then(
            // On récupère la réponse en JSON
            response => response.json()
         ).then(data => {
            if (data.success) {
               this.parentElement.parentElement.remove();
               // On affiche un message invitant à recharger la page
               let divDuFileInput = document.querySelector("#divDuFileInput");
               let divChamp = document.createElement("div");
               let pMessage = document.createElement("p");

               divChamp.classList.add("champ", "constraints-messages");


               divDuFileInput.appendChild(divChamp);
               divChamp.appendChild(pMessage);
               pMessage.innerText = "Pour ajouter une image, veuillez recharger la page svp";


               // On recréé le sélecteur de fichier (inputFile)
               // let divDuFileInput = document.querySelector("#divDuFileInput");
               // let divChamp = document.createElement("div");
               // let divMessage = document.createElement("div");
               // let divWrapInput = document.createElement("div");
               // let inputFile = document.createElement("input");

               // divChamp.classList.add("champ", "constraints-messages")
               // divMessage.title = "Image carrée uniquement";
               // divMessage.innerHTML = "Image carrée uniquement";
               // inputFile.classList = "image";
               // inputFile.type = "file";
               // inputFile.id = "membre_asso_photo";
               // inputFile.name = "membre_asso[photo]";

               // divDuFileInput.prepend(divChamp);
               // divChamp.prepend(divMessage);
               // divChamp.appendChild(divWrapInput);
               // divWrapInput.appendChild(inputFile);
            }
            else {
               alert(data.error)
            }
         }).catch(e => alert(e))
      }
   });
}
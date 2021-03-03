window.onload = () => {
   // Gestion des liens "Supprimer"
   let links = document.querySelectorAll("[data-delete]")

   // On boucle sur links
   for (const link of links) {
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
               if (data.success)
                  this.parentElement.remove()
               else
                  alert(data.error)
            }).catch(e => alert(e))
         }
      })
   }
}
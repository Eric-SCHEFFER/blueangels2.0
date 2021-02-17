// Au clic du bouton, on ouvre/ferme son sous-menu 
function toggleMenu(iDMenu) {
   let dropDownTarget = document.getElementById(iDMenu);
   let dropdowns = document.getElementsByClassName('dropdown-content');
   let i;
   for (i = 0; i < dropdowns.length; i++) {
      let openDropdown = dropdowns[i];
      // On ferme tous les menus dropdown ouvert, excepté celui du bouton cliqué
      if (openDropdown != dropDownTarget) {
         if (openDropdown.classList.contains('show')) {
            openDropdown.classList.remove('show');
         }
      }
   }
   // Bascule On/Off du menu dropdown au clic du le bouton
   dropDownTarget.classList.toggle("show");
}


// Ferme le menu dropdown si on clique n'importe où sauf sur son bouton
window.onclick = function (e) {
   if (!e.target.matches('.btDropdown')) {
      let dropdowns = document.getElementsByClassName('dropdown-content');
      let i;
      for (i = 0; i < dropdowns.length; i++) {
         let openDropdown = dropdowns[i];
         if (openDropdown.classList.contains('show')) {
            openDropdown.classList.remove('show');
         }
      }
   }
}


//  ================= Menu burger =====================

//  ============ Fonctions ===============

function appuiIconeBurger() {
   nav.classList.toggle('visible');
}


//  ============ Affectation des objets ===============
let nav = document.getElementById('nav');



//  ============ Démarrage ===============
document.getElementById("burger").addEventListener("click", appuiIconeBurger);
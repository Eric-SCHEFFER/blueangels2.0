//  ============ Sous-menu ===============

let boutons = document.querySelectorAll(".btDropdown");

for (let bouton = 0; bouton < boutons.length; bouton++) {
   boutons[bouton].addEventListener("click", clicBouton);
}

function clicBouton() {
   // Affiche/masque le sous-menu
   if (this.classList.contains("visible")) {
      this.classList.remove("visible");
   } else {
      this.classList.add("visible");
   }
   // On ferme tous les sous-menu ouverts, excepté celui de notre bouton cliqué
   for (let bouton = 0; bouton < boutons.length; bouton++) {
      if (boutons[bouton] != this) {
         // Affiche/masque le sous-menu
         if (boutons[bouton].classList.contains("visible")) {
            boutons[bouton].classList.remove("visible");
         }
      }
   }
}


document.addEventListener("click", clicAilleurs);

// Ferme le menu dropdown si on clique n'importe où sauf sur son bouton et sur un autre bouton de sous-menu
function clicAilleurs() {
   if (!this.activeElement.classList.contains("btDropdown")) {
      for (let bouton = 0; bouton < boutons.length; bouton++) {
         if (boutons[bouton].classList.contains("visible")) {
            boutons[bouton].classList.remove("visible");
         }
      }
   }
}










//  ================= Menu burger =====================

//  ============ Fonctions ===============

function appuiIconeBurger() {
   if (oNav.classList.contains('visible')) {
      closeMenuBurger();
   } else {
      openMenuBurger();
   }
}

function openMenuBurger() {
   oNav.classList.add('visible');
   oBody.classList.add('no-click-no-scroll');
   oNav.classList.add('restore-click');
   oBurger.classList.add('restore-click');
   oBurger.classList.add('burger-menu-ouvert');
   oburgerBarre1.classList.add('burgerBarre1-menu-ouvert');
   oburgerBarre2.classList.add('burgerBarre2-menu-ouvert');
   oburgerBarre3.classList.add('burgerBarre3-menu-ouvert');
   oBodyDark.classList.add('bgDark');
}

function closeMenuBurger() {
   oNav.classList.remove('visible');
   oBody.classList.remove('no-click-no-scroll');
   oBurger.classList.remove('burger-menu-ouvert');
   oburgerBarre1.classList.remove('burgerBarre1-menu-ouvert');
   oburgerBarre2.classList.remove('burgerBarre2-menu-ouvert');
   oburgerBarre3.classList.remove('burgerBarre3-menu-ouvert');
   oBodyDark.classList.remove('bgDark');
}

//  ============ Affectation des objets ===============
let oBody = document.getElementById("bodyId");
let oBodyDark = document.getElementById("bodyDark");
let oNav = document.getElementById('nav');
let oBurger = document.getElementById("burger");
let oburgerBarre1 = document.getElementById("burgerBarre1");
let oburgerBarre2 = document.getElementById("burgerBarre2");
let oburgerBarre3 = document.getElementById("burgerBarre3");

//  ============ Détection clic sur burger ===============
document.getElementById("burger").addEventListener("click", appuiIconeBurger);

// ==== Détection clic ailleurs que sur le burger, et le fond du nav, pour fermer le menuburger ====
document.addEventListener('click', function (event) {
   if (oNav.classList.contains('visible')) {
      let onCliqueSurBurger = oBurger.contains(event.target);
      let onCliqueDansVideMenu = oNav.contains(event.target);
      if (!onCliqueSurBurger && !onCliqueDansVideMenu) {
         closeMenuBurger();
      }
   }
});
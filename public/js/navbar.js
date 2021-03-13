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
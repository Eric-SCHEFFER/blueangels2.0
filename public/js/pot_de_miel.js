// On masque la div grand-parent du champ leurre input
let champInput = document.querySelector('#contact_email');
let blocLigneGrandParent = champInput.parentNode.parentNode;

blocLigneGrandParent.style.display = ("none");

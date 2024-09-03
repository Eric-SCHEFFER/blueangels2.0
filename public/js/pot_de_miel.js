
// On masque la div grand-parent du champ leurre input
let champInput = document.querySelector('#contact_email');
let blocLigneGrandParent = champInput.parentNode.parentNode;

blocLigneGrandParent.style.visibility = ("hidden");
blocLigneGrandParent.style.height = ("0");
blocLigneGrandParent.style.width = ("0");


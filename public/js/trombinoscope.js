// Reçoit le clic sur une card, avec son id en paramètre, et bascule sur la class qui développe la description
function cardClick(id) {
   let targetCard = document.getElementById('card-' + id);
   targetCard.classList.toggle('card-click');
   let cards = document.getElementsByClassName('card');
   for (let i = 0; i < cards.length; i++) {
      const card = cards[i];
      if (card != targetCard) {
         card.classList.remove('card-click');
      }
   }
}


// Détecte le clic ailleurs que sur une card pour fermer la card encore ouverte
document.addEventListener('click', function (e) {
   // let cible = document.getElementsByClassName('card')[0].contains(e.target);
   // console.log(cible);
   let cards = document.getElementsByClassName('card');
   if (!e.target.closest('.card')) {
      for (let i = 0; i < cards.length; i++) {
         const card = cards[i];
         if (card.classList.contains('card-click')) {
            card.classList.remove('card-click');
         }
      }
   }
});
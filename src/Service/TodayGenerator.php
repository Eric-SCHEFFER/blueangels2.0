<?php

namespace App\Service;

use DateTime;

class TodayGenerator
{
   /**
    * Génère une dateTime du jour fictive à des fins de tests. Accessible pour toute notre appli symfony.
    * Entrer la date souhaitée dans les paramètres de l'objet DateTime, dans la méthode,
    * sous la forme: '2020-12-31' ou par ex '2020-12-31 22:31:05' si l'on veut ajouter l'heure, mn, sec.
    * ('2020-12-31' équivaut à '2020-12-31 00:00:00').
    * Laisser vide pour renvoyer la dateTime réelle du serveur.
    */
   public function generateAToday()
   {
      $today = new DateTime('2021-3-5 22:31:05');
      return $today;
   }
}

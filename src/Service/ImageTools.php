<?php

namespace App\Service;

class ImageTools
{
   /**
    * Crée une miniature d'une image d'un fichier jpg ou png.
    * Paramètres: 1: Chemin complet de l'image source (jpg ou png).
    * 2: Chemin complet de l'image de sortie (cible).
    * 3: Largeur souhaitée en px.
    */
   public function createMiniature(string $imageSource, string $imageCible, int $targetWidth)
   {
      // On recupère l'extension, et on minimise les caractères
      $ext = strtolower(pathinfo($imageSource, PATHINFO_EXTENSION));
      // On stocke dans des variables les noms des fonctions à lancer plus tard, selon l'extension de l'image
      if ($ext == "jpg" || $ext == "jpeg") {
         $imagecreatefrom = "imagecreatefromjpeg";
         $imageSortie = "imagejpeg";
      } elseif ($ext == "png") {
         $imagecreatefrom = "imagecreatefrompng";
         $imageSortie = "imagepng";
      } else {
         // On retourne une erreur, car ce n'est ni une image jpg, ni png
         return "Image non valide (jpg ou png uniquement)";
      }

      $sourceSize = getimagesize($imageSource);
      $portraitMalOriente = false;
      // On détecte si une image jpg est en portrait, et si elle est mal orientée
      if ($imageSortie == "imagejpeg") {
         if (isset(exif_read_data($imageSource, 'ANY_TAG')['Orientation'])) {
            $portraitMalOriente = exif_read_data($imageSource, 'ANY_TAG')['Orientation'];
            if ($portraitMalOriente == 6 && $sourceSize[0] > $sourceSize[1]) {
               $portraitMalOriente = true;
            } else {
               $portraitMalOriente = false;
            }
         }
      }
      if ($portraitMalOriente) {
         $sourceWidth = $sourceSize[1];
         $sourceHeight = $sourceSize[0];
      } else {
         $sourceWidth = $sourceSize[0];
         $sourceHeight = $sourceSize[1];
      }
      // On calcule les dimensions de la miniature, et on lance les fonctions php de création de miniature
      $targetHeight = ($targetWidth / $sourceWidth) * $sourceHeight;
      // ERR Selon la taille de l'image, si la RAM n'est pas pas suffisante pour créer la miniature, celà génère une erreur dans symfony
      // Constaté sur une image d'environ 3,4 Mio, avec une RAM de 500 Mo (chez Ionos)
      // warning: imagecreatefromjpeg(): gd-jpeg: jpeg library reports unrecoverable error: insufficient memory (case 4)
      // Mise en place d'un try catch dans le code appelant cette méthode
      $imgIn = $imagecreatefrom($imageSource);
      // On pivote l'image de 90° dans le sens horaire, si nécéssaire
      if ($portraitMalOriente) {
         $imgIn = imagerotate($imgIn, -90, 0);
      }
      $imgOut = imagecreatetruecolor($targetWidth, $targetHeight);
      imagecopyresampled($imgOut, $imgIn, 0, 0, 0, 0, $targetWidth, $targetHeight, $sourceWidth, $sourceHeight);
      $imageSortie($imgOut, $imageCible);
      return;
   }
}

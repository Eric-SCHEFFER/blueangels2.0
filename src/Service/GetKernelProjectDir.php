<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GetKernelProjectDir
{
   private $urlGeneratorInterface;
   public function __construct(
      UrlGeneratorInterface $urlGeneratorInterface
   ) {
      $this->urlGeneratorInterface = $urlGeneratorInterface;
   }
   /**
    * 
    * 
    */
   public function foundKernelProjectDir()
   {
      $sortie = $this->urlGeneratorInterface->generate('home', [], urlGeneratorInterface::ABSOLUTE_URL);
      dd($sortie);
      return;
   }
}

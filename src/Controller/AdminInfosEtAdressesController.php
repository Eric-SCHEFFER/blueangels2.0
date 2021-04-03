<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\InfosEtAdresses;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\InfosEtAdressesType;

class AdminInfosEtAdressesController extends AbstractController
{
   public function __construct(EntityManagerInterface $em)
   {
      $this->em = $em;
   }

   /**
    * @Route("/admin/infosEtAdresses", name="admin.infosEtAdresses")
    */
   // ======== ÉDITER infosEtAdresses ========
   public function edit(Request $request)
   {
      // On récupère la table infosEtAdresses
      $infosEtAdresses = $this->getDoctrine()->getRepository(InfosEtAdresses::class)->findAll();
      // Comme il n'y a qu'une seule ligne, ce sera l'index 0 du tableau
      $infosEtAdresses = $infosEtAdresses[0];
      $form = $this->createForm(InfosEtAdressesType::class, $infosEtAdresses);
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
         $this->em->persist($infosEtAdresses);
         $this->em->flush();
         $this->addFlash('succes', 'infosEtAdresses mis à jour avec succès');
         return $this->redirectToRoute('admin');
      }
      return $this->render('admin/infosEtAdresses/infosEtAdressesForm.html.twig', [
         'infosEtAdresses' => $infosEtAdresses,
         'form' => $form->createView()
      ]);
   }
}

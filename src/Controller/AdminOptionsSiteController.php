<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\OptionsSite;
use App\Repository\OptionsSiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\OptionsSiteType;

class AdminOptionsSiteController extends AbstractController
{
   public function __construct(
    OptionsSiteRepository $optionsSiteRepository,
      EntityManagerInterface $em
   ) {
      $this->OptionsSiteRepository = $optionsSiteRepository;
      $this->em = $em;
   }
   /**
    * @Route("/admin/optionsSite", name="admin.options.site")
    */
   public function loadAllOptionsSite(): Response
   {
      $optionsSite = $this->getDoctrine()->getRepository(OptionsSite::class)->findBy([], ["nom" => "ASC"]);
      return $this->render('admin/optionsSite/adminOptionsSite.html.twig', [
         'optionsSite' => $optionsSite,
      ]);
   }


   /**
    * Nouvelle option
    * @Route("/admin/optionsSite/nouvelle", name="admin.optionsSite.nouvelle")
    */
   public function new(Request $request)
   {
      $optionSite = new OptionsSite();
      $form = $this->createForm(OptionsSiteType::class, $optionSite);
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
         $this->em->persist($optionSite);
         $this->em->flush();
         $this->addFlash('succes', 'Option créée avec succès');
         // TODO: Sécuriser la redirection en s'assurant surant que le referer vient bien de notre site.
         // Est-ce que ça fonctionne en https ?
         return $this->redirect($request->request->get('referer'));
      }
      return $this->render('admin/optionsSite/nouvelle.html.twig', [
         'form' => $form->createView()
      ]);
   }

   /**
    * Éditer une catégorie
    * @Route("/admin/optionsSite/edit/{id}", name="admin.optionsSite.edit", methods="GET|POST")
    * @param OptionsSite $optionsSite
    * @param Request $request
    * @return \Symfony\Component\HttpFoundation\Response
    */
   public function edit(OptionsSite $optionsSite, Request $request)
   {
      $form = $this->createForm(OptionsSiteType::class, $optionsSite);
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
         $this->em->persist($optionsSite);
         $this->em->flush();
         $this->addFlash('succes', 'Option modifiée avec succès');
         // TODO: Sécuriser la redirection en s'assurant surant que le referer vient bien de notre site.
         // Est-ce que ça fonctionne en https ?
         return $this->redirect($request->request->get('referer'));
      }
      return $this->render('admin/optionsSite/edit.html.twig', [
         'form' => $form->createView()
      ]);
   }

   /**
    * Supprimer une option
    * @Route("/admin/optionSite/edit/{id}", name="admin.optionSite.delete", methods={"DELETE"})
    * @param OptionsSite $optionSite
    * @return \Symfony\Component\HttpFoundation\Response
    */
   public function delete(OptionsSite $optionSite, Request $request)
   {
      // Vérif token pour sécuriser la suppression d'une catégorie
      if ($this->isCsrfTokenValid('delete' . $optionSite->getId(), $request->get('_token'))) {
         // On supprime l'option
         $this->em->remove($optionSite);
         $this->em->flush();
         $this->addFlash('succes', '"' . $optionSite->getNom() . '"' . ' supprimée avec succès');
         //return new HttpFoundationResponse('Suppression');
      }
      return $this->redirectToRoute('admin');
   }
}

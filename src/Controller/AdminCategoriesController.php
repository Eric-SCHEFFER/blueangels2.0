<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\CategoriesArticle;
use App\Repository\CategoriesArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CategorieType;

class AdminCategoriesController extends AbstractController
{
   public function __construct(
      CategoriesArticleRepository $categoriesArticleRepository,
      EntityManagerInterface $em
   ) {
      $this->CategoriesArticleRepository = $categoriesArticleRepository;
      $this->em = $em;
   }
   /**
    * @Route("/admin/cadegories", name="admin.categories")
    */
   public function loadAllCategoriesArticles(): Response
   {
      $categories = $this->getDoctrine()->getRepository(CategoriesArticle::class)->findBy([], ["nom" => "ASC"]);
      return $this->render('admin/categories/adminCategories.html.twig', [
         'categories' => $categories,
      ]);
   }


   /**
    * Nouvelle catégorie
    * @Route("/admin/categories/nouvelle", name="admin.categories.nouvelle")
    */
   public function new(Request $request)
   {
      $categorie = new CategoriesArticle();
      $form = $this->createForm(CategorieType::class, $categorie);
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
         $this->em->persist($categorie);
         $this->em->flush();
         $this->addFlash('succes', 'Catégorie créée avec succès');
         // TODO: Sécuriser la redirection en s'assurant surant que le referer vient bien de notre site.
         // Est-ce que ça fonctionne en https ?
         return $this->redirect($request->request->get('referer'));
      }
      return $this->render('admin/categories/nouvelle.html.twig', [
         'form' => $form->createView()
      ]);
   }

   /**
    * Éditer une catégorie
    * @Route("/admin/categories/edit/{id}", name="admin.categories.edit", methods="GET|POST")
    * @param CategoriesArticle $categories
    * @param Request $request
    * @return \Symfony\Component\HttpFoundation\Response
    */
   public function edit(CategoriesArticle $categorie, Request $request)
   {
      $form = $this->createForm(CategorieType::class, $categorie);
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
         $this->em->persist($categorie);
         $this->em->flush();
         $this->addFlash('succes', 'Catégorie modifiée avec succès');
         // TODO: Sécuriser la redirection en s'assurant surant que le referer vient bien de notre site.
         // Est-ce que ça fonctionne en https ?
         return $this->redirect($request->request->get('referer'));
      }
      return $this->render('admin/categories/edit.html.twig', [
         'form' => $form->createView()
      ]);
   }

   /**
    * Supprimer une catégorie
    * @Route("/admin/categories/edit/{id}", name="admin.categorie.delete", methods={"DELETE"})
    * @param CategoriesArticle $categorie
    * @return \Symfony\Component\HttpFoundation\Response
    */
   public function delete(CategoriesArticle $categorie, Request $request)
   {
      // TODO: Rajouter un test pour éviter une erreur lors de la suppression d'une catégorie qui contient encore des enfants. Tester avec un count des articles de la catégorie.
      // Vérif token pour sécuriser la suppression d'une catégorie
      if ($this->isCsrfTokenValid('delete' . $categorie->getId(), $request->get('_token'))) {
         // On supprime la catégorie
         $this->em->remove($categorie);
         $this->em->flush();
         $this->addFlash('succes', '"' . $categorie->getNom() . '"' . ' supprimée avec succès');
         //return new HttpFoundationResponse('Suppression');
      }
      return $this->redirectToRoute('admin');
   }
}

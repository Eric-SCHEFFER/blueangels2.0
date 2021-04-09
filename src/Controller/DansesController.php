<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticlesRepository;
use App\Repository\CategoriesArticleRepository;

class DansesController extends AbstractController
{
    public function __construct(
        ArticlesRepository $articlesRepository,
        CategoriesArticleRepository $categoriesArticleRepository
    ) {
        $this->articlesRepository = $articlesRepository;
        $this->categoriesArticleRepository = $categoriesArticleRepository;
    }

    /**
     * @Route("/danses", name="danses")
     */
    public function loadDanses(): Response
    {
        $categorie = $this->categoriesArticleRepository->findOneBy(['nom' => 'Danses']);
        $categorieId = $categorie->getId();
        // On va sur la route qui affiche les articles selon la catégorie passée en id, et c'est sur ArticlesByCategorieController que s'effectue la logique
        return $this->redirectToRoute('categorie.articles', ['idCategorie' => $categorieId]);
    }


    /**
     * Cherche le premier article en fonction de la catégorie et du titre
     */
    // private function findFirstArticleByCategorieAndTitle($quelledDanse)
    // {
    //     $repoArticles = $this->getDoctrine()->getRepository(Articles::class);
    //     $repoCat = $this->getDoctrine()->getRepository(CategoriesArticle::class);
    //     $imagesArticleRepository = $this->getDoctrine()->getRepository(ImagesArticle::class);
    //     // On recherche dans CategoriesArticle, la catégorie "Danses".  
    //     $categorie = $repoCat->findOneBy(array('nom' => 'Danses'));
    //     $findArticle = $repoArticles->findOneBy(
    //         [
    //             'categories_article' => $categorie,
    //             'actif' => true,
    //             'titre' => $quelledDanse,
    //             'linked_page' => true,
    //         ],
    //         ['created_at' => "DESC"]
    //     );
    //     if (empty($findArticle)) {
    //         // Erreur 404
    //         throw $this->createNotFoundException('L\'article est introuvable');
    //     }
    //     $id = $findArticle->getId();
    //     $article = $repoArticles->find($id);
    //     $images = $imagesArticleRepository->findBy(['articles' => $article]);
    //     return
    //         $article;
    // }
}

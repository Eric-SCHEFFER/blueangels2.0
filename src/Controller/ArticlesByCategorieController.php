<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticlesRepository;
use App\Repository\CategoriesArticleRepository;

class ArticlesByCategorieController extends AbstractController
{
    public function __construct(
        ArticlesRepository $articlesRepository,
        CategoriesArticleRepository $categoriesArticleRepository
    ) {
        $this->articlesRepository = $articlesRepository;
        $this->categoriesArticleRepository = $categoriesArticleRepository;
    }

    /**
     * @Route("/categorieArticles/{idCategorie}", name="categorie.articles")
     */
    public function loadArticles($idCategorie): Response
    {
        $categorie = $this->categoriesArticleRepository->find(['id' => $idCategorie]);
        $articles = $this->getArticlesByCategorie($idCategorie);
        return $this->render('articlesByCategorie/articlesByCategorie.html.twig', [
            'articles' => $articles,
            'nomCategorie' => $categorie->getNom(),
        ]);
    }


    /**
     * On récupère les articles pour la catégorie en question
     */
    private function getArticlesByCategorie($idCategorie)
    {
        $articles = $this->articlesRepository->findBy(
            [
                'actif' => true,
                'categories_article' => $idCategorie,
            ],
            ['created_at' => "DESC"],
        );
        return
            $articles;
    }

    // /**
    //  * On récupère les articles pour la catégorie en question, avec les épinglés en premier
    //  */
    // private function getArticlesByCategorie($idCategorie, $combien)
    // {
    //     $pinnedArticles = $this->articlesRepository->findPinnedActifsListedArticlesByCategorie($idCategorie, $combien);
    //     $nonPinnedArticles = [];
    //     // On complète avec des articles non-épinglés
    //     $nonPinnedArticles = $this->articlesRepository->findActifsListedArticlesByCategorie($idCategorie, $combien);
    //     // On fusionne $pinnedArticles et $nonPinnedArticless
    //     $articles = array_merge($pinnedArticles, $nonPinnedArticles);
    //     return
    //         $articles;
    // }
}

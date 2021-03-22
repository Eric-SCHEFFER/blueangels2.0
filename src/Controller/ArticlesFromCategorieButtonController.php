<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticlesRepository;
use App\Repository\CategoriesArticleRepository;

class ArticlesFromCategorieButtonController extends AbstractController
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
        $articles = $this->articlesRepository->findBy(
            [
                'categories_article' => $categorie,
                'actif' => true,
            ],
            ['created_at' => "DESC"]
        );

        if (empty($articles)) {
            // Erreur 404
            throw $this->createNotFoundException('L\'article est introuvable');
        }
        return $this->render('articlesFromCategorieButton/articlesFromCategorieButton.html.twig', [
            'articles' => $articles,
            'nomCategorie' => $categorie->getNom(),
        ]);
    }
}

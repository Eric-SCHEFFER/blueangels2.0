<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticlesRepository;
use App\Repository\CategoriesArticleRepository;

class PartenairesController extends AbstractController
{
    public function __construct(
        ArticlesRepository $articlesRepository,
        CategoriesArticleRepository $categoriesArticleRepository
    ) {
        $this->articlesRepository = $articlesRepository;
        $this->categoriesArticleRepository = $categoriesArticleRepository;
    }
    /**
     * @Route("/partenaires", name="partenaires")
     */
    public function loadArticles(): Response
    {
        $categorie = $this->categoriesArticleRepository->findOneBy(['nom' => 'Partenaires']);
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
        return $this->render('partenaires/partenaires.html.twig', [
            'menu_courant' => 'partenaires',
            'articles' => $articles,
        ]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticlesRepository;

class ArticlesController extends AbstractController
{
    public function __construct(
        ArticlesRepository $articlesRepository
    ) {
        $this->articlesRepository = $articlesRepository;
    }
    /**
     * @Route("/articles", name="articles")
     */
    public function index(): Response
    {
        // On récupère tous les articles qui ont le droit de s'afficher (visibles)
        $articles = $this->articlesRepository->findBy(
            [
                'actif' => true,
                // 'listed' => true,
            ],
            ['created_at' => "DESC"]
        );
        // Si on veut mettre les épinglés devant: On récupère tous les articles qui ont le droit de s'afficher (visibles, listés en page d'accueil, et épinglés) dont en premier les épinglés s'il y en a
        // $articles = $this->getArticles();
        return $this->render('articles/index.html.twig', [
            'menu_courant' => 'articles',
            'articles' => $articles,
        ]);
    }

    /**
     * On récupère les articles qui ont le droit de s'afficher, dont en priorité des épinglés s'il y en a.
     */
    // private function getArticles()
    // {
    // Articles épinglés
    // $pinnedArticles = $this->articlesRepository->findAllPinnedActifsListedArticles();
    // Articles non-épinglés
    // $nonPinnedArticles = $this->articlesRepository->findAllActifsListedArticles();
    // On fusionne les deux tableaux $pinnedArticles et $nonPinnedArticles dans $articles.
    // $articles = array_merge($pinnedArticles, $nonPinnedArticles);
    // return
    // $articles;
    // }
}

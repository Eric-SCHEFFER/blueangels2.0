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
        // On récupère tous les articles, dont en premier les épinglés s'il y en a
        $articles = $this->getArticles();
        return $this->render('articles/index.html.twig', [
            'menu_courant' => 'articles',
            'articles' => $articles,

        ]);
    }

    // TODO: factoriser cette fonction qui est utilisée dans plusieurs controleurs, en la mettant dans un service.
     /**
     * On récupère les articles, dont en priorité des épinglés s'il y en a.
     */
    private function getArticles()
    {
        // Articles épinglés
        $pinnedArticles = $this->articlesRepository->findAllPinnedArticles();
        // Articles non-épinglés
        $nonPinnedArticles = $this->articlesRepository->findAllArticles();
        // On fusionne les deux tableaux $pinnedArticles et $nonPinnedArticles dans $articles.
        $articles = array_merge($pinnedArticles, $nonPinnedArticles);
        return
            $articles;
    }
}

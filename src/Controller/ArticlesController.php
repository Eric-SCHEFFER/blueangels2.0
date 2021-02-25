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
        // On récupère les articles
        $articles = $this->articlesRepository->findBy([], ['created_at' => 'DESC']);
        return $this->render('articles/index.html.twig', [
            'menu_courant' => 'articles',
            'articles' => $articles,

        ]);
    }
}

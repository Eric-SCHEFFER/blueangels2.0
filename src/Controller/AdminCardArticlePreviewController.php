<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticlesRepository;

class AdminCardArticlePreviewController extends AbstractController
{
    public function __construct(
        ArticlesRepository $articlesRepository
    ) {
        $this->articlesRepository = $articlesRepository;
    }

    #[Route('admin/preview/card_article/{id}', name: 'admin.preview.card.article')]
    public function loadArticle($id, ArticlesRepository $articlesRepository): Response
    {
        $article = $this->articlesRepository->find($id);
        return $this->render('admin/preview/cardArticle.html.twig', [
            'article' => $article,
        ]);
    }
}

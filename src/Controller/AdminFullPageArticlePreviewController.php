<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticlesRepository;

class AdminFullPageArticlePreviewController extends AbstractController
{
    public function __construct(
        ArticlesRepository $articlesRepository
    ) {
        $this->articlesRepository = $articlesRepository;
    }

    #[Route('admin/preview/fullPageArticle/{id}', name: 'admin.preview.fullPageArticle')]
    public function loadArticle($id, ArticlesRepository $articlesRepository): Response
    {
        $article = $this->articlesRepository->find($id);
        return $this->render('admin/preview/fullPageArticle.html.twig', [
            'article' => $article,
        ]);
    }
}

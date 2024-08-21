<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticlesRepository;
use App\Repository\ImagesArticleRepository;


class AdminFullPageEventPreviewController extends AbstractController
{
    public function __construct(
        ArticlesRepository $articlesRepository,
        ImagesArticleRepository $imagesArticleRepository
    ) {
        $this->articlesRepository = $articlesRepository;
        $this->imagesArticleRepository = $imagesArticleRepository;
    }

    #[Route('admin/preview/fullPageArticle/{id}', name: 'admin.preview.fullPageArticle')]
    public function loadArticle($id, ArticlesRepository $articlesRepository): Response
    {
        $article = $this->articlesRepository->find($id);
        $images = $this->imagesArticleRepository->findBy(['articles' => $article]);

        return $this->render('admin/preview/fullPageArticle.html.twig', [
            'article' => $article,
            'images' => $images
        ]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminArticlePreviewController extends AbstractController
{
    #[Route('admin/preview/card_article/{id}', name: 'admin.preview.card.article')]
    public function loadCardArticlePreview(): Response
    {
        return $this->render('admin/preview/cardArticle.html.twig', [
            'controller_name' => 'AdminArticlePreviewController',
        ]);
    }
}

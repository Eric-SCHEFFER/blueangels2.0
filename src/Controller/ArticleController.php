<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Articles;
use App\Repository\ImagesArticleRepository;
use DateTime;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article/{id}", name="article")
     */
    public function articleLoad($id, ImagesArticleRepository $imagesArticleRepository): Response
    {
        $repo = $this->getDoctrine()->getRepository(Articles::class);
        $article = $repo->find($id);
        $images = $imagesArticleRepository->findBy(['articles' => $article]);
        return $this->render('article/index.html.twig', [
            'article' => $article,
            'images' => $images,
        ]);
    }
}

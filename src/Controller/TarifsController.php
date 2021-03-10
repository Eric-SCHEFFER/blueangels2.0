<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Articles;
use App\Repository\ImagesArticleRepository;

class TarifsController extends AbstractController
{
    /**
     * @Route("/tarifs/", name="tarifs")
     */
    public function articleLoad(ImagesArticleRepository $imagesArticleRepository): Response
    {
        $repo = $this->getDoctrine()->getRepository(Articles::class);
        $article = $repo->find('47');
        $images = $imagesArticleRepository->findBy(['articles' => $article]);
        return $this->render('article/index.html.twig', [
            'article' => $article,
            'images' => $images,
        ]);
    }
}

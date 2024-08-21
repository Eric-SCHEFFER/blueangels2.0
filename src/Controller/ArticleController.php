<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Articles;
use App\Repository\ImagesArticleRepository;
use App\Repository\CategoriesArticleRepository;
use DateTime;
use Doctrine\ORM\Mapping\Id;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article/{id}", name="article")
     */
    public function articleLoad($id, ImagesArticleRepository $imagesArticleRepository, CategoriesArticleRepository $ategoriesArticleRepository): Response
    {
        $repo = $this->getDoctrine()->getRepository(Articles::class);
        $article = $repo->findOneBy(
            [
                'id' => $id,
                'actif' => true
            ]
        );
        if (empty($article)) {
            // Erreur 404
            throw $this->createNotFoundException('L\'article n\'est pas actif');
        }

        // On récupère le nombres d'articles de la catégorie de l'article
        $articlesByCat = $repo->findBy(
            [
                'categories_article' => $article->getCategoriesArticle()->getId(),
                'actif' => true
            ],
            ['created_at' => 'ASC',]
        );
        $nbreArticlesByCat = count($articlesByCat);

        // On construit un tableau indicé, contenant les id classés chronologiquement, des articles de la catégorie
        for ($i = 0; $i < $nbreArticlesByCat; ++$i) {
            $articlesByCatId[$i] = $articlesByCat[$i]->getId();
        }

        // On parcours le tableau créé, et on récupère s'il existent, les id des articles antérieurs et postérieurs chronologiquement
        $previousArticleByDateId = null;
        $nextArticleByDateId = null;
        $n = 0;
        foreach ($articlesByCatId as $articleByCatId) {
            if ($articleByCatId == $id) {
                $numArticle = $n + 1;
                if (isset($articlesByCatId[$n - 1])) {
                    $previousArticleByDateId = $articlesByCatId[$n - 1];
                }
                if (isset($articlesByCatId[$n + 1])) {
                    $nextArticleByDateId = $articlesByCatId[$n + 1];
                }
                break;
            }
            ++$n;
        }

        $images = $imagesArticleRepository->findBy(['articles' => $article]);

        return $this->render('article/index.html.twig', [
            'article' => $article,
            'images' => $images,
            'nbreArticlesByCat' => $nbreArticlesByCat,
            'previousArticleByDateId' => $previousArticleByDateId,
            'nextArticleByDateId' => $nextArticleByDateId,
            'numArticle' => $numArticle
        ]);
    }
}

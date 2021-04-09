<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Articles;
use App\Entity\CategoriesArticle;

class StagesController extends AbstractController
{
    /**
     * @Route("/stages/", name="stages")
     */
    public function findFirstArticleByCategorie(): Response
    {
        $repoArticles = $this->getDoctrine()->getRepository(Articles::class);
        $repoCat = $this->getDoctrine()->getRepository(CategoriesArticle::class);
        // On recherche dans CategoriesArticle, la catégorie "Stages".  
        $categorie = $repoCat->findOneBy(array('nom' => 'Stages'));
        // On cherche l'article actif le plus récent dans cette catégorie
        $findArticle = $repoArticles->findOneBy(
            [
                'categories_article' => $categorie,
                'actif' => true,
            ],
            ['created_at' => "DESC"]
        );
        if (empty($findArticle)) {
            // Erreur 404
            throw $this->createNotFoundException('L\'article est introuvable');
        }
        $id = $findArticle->getId();
        $article = $repoArticles->find($id);
        return $this->render('article/index.html.twig', [
            'article' => $article,
        ]);
    }
}

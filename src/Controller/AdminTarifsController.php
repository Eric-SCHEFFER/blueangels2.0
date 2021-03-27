<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Articles;
use App\Entity\CategoriesArticle;

class AdminTarifsController extends AbstractController
{
    /**
     * @Route("/admin/tarifs", name="admin.tarifs")
     */
    public function findAllArticlesByCategorie(): Response
    {
        $repoArticles = $this->getDoctrine()->getRepository(Articles::class);
        $repoCat = $this->getDoctrine()->getRepository(CategoriesArticle::class);
        // On recherche dans CategoriesArticle, la catégorie "Tarifs".  
        $categorie = $repoCat->findOneBy(array('nom' => 'Tarifs'));
        $categorieId = $categorie->getId();
        // On cherche tous les articles dans cette catégorie
        $articles = $repoArticles->findBy(
            [
                'categories_article' => $categorie,
            ],
            ['created_at' => 'DESC']
        );
        return $this->render('admin/tarifs/index.html.twig', [
            'articles' => $articles,
            'categorieId' => $categorieId,
        ]);
    }
}

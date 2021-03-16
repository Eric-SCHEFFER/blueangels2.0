<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Articles;
use App\Entity\CategoriesArticle;
use App\Entity\ImagesArticle;

class DansesController extends AbstractController
{
    /**
     * @Route("/salsa", name="salsa")
     * Charge l'article pour la page salsa
     */
    public function loadSalsa(): Response
    {
        $article = $this->findFirstArticleByCategorieAndTitle('La salsa');
        return $this->render('article/index.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/rock", name="rock")
     * Charge l'article pour la page Rock n roll
     */
    public function loadRock(): Response
    {
        $article = $this->findFirstArticleByCategorieAndTitle('Le rock n roll');
        return $this->render('article/index.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/bachata", name="bachata")
     * Charge l'article pour la page Bachata
     */
    public function loadBachata(): Response
    {
        $article = $this->findFirstArticleByCategorieAndTitle('La bachata');
        return $this->render('article/index.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/chacha", name="chacha")
     * Charge l'article pour la page Cha-cha
     */
    public function loadChaCha(): Response
    {
        $article = $this->findFirstArticleByCategorieAndTitle('Le cha-cha');
        return $this->render('article/index.html.twig', [
            'article' => $article,
        ]);
    }


    /**
     * Cherche le premier article en fonction de la catégorie et du titre
     */
    private function findFirstArticleByCategorieAndTitle($quelledDanse)
    {
        $repoArticles = $this->getDoctrine()->getRepository(Articles::class);
        $repoCat = $this->getDoctrine()->getRepository(CategoriesArticle::class);
        $imagesArticleRepository = $this->getDoctrine()->getRepository(ImagesArticle::class);
        // On recherche dans CategoriesArticle, la catégorie "Danses".  
        $categorie = $repoCat->findOneBy(array('nom' => 'Danses'));
        $findArticle = $repoArticles->findOneBy(
            [
                'categories_article' => $categorie,
                'actif' => true,
                'titre' => $quelledDanse,
                'linked_page' => true,
            ],
            ['created_at' => "DESC"]
        );
        if (empty($findArticle)) {
            // Erreur 404
            throw $this->createNotFoundException('L\'article est introuvable');
        }
        $id = $findArticle->getId();
        $article = $repoArticles->find($id);
        $images = $imagesArticleRepository->findBy(['articles' => $article]);
        return
            $article;
    }
}

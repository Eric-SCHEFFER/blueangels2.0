<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticlesRepository;
use App\Repository\CategoriesArticleRepository;

class HistoriqueAssoController extends AbstractController
{
    public function __construct(
        ArticlesRepository $articlesRepository,
        CategoriesArticleRepository $categoriesArticleRepository
    ) {
        $this->articlesRepository = $articlesRepository;
        $this->categoriesArticleRepository = $categoriesArticleRepository;
    }

    /**
     * @Route("/historiqueAsso", name="historique.asso")
     */
    public function loadHistoriqueAsso(): Response
    {
        $categorie = $this->categoriesArticleRepository->findOneBy(['nom' => 'Historique asso']);
        $categorieId = $categorie->getId();
        // On va sur la route qui affiche les articles selon la catégorie passée en id, et c'est sur ArticlesByCategorieController que s'effectue la logique
        return $this->redirectToRoute('categorie.articles', ['idCategorie' => $categorieId]);
    }
}

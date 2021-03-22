<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticlesRepository;
use App\Repository\CategoriesArticleRepository;

class PartenairesController extends AbstractController
{
    public function __construct(
        ArticlesRepository $articlesRepository,
        CategoriesArticleRepository $categoriesArticleRepository
    ) {
        $this->articlesRepository = $articlesRepository;
        $this->categoriesArticleRepository = $categoriesArticleRepository;
    }

    /**
     * @Route("/partenairessssss", name="partenaires")
     */
    public function loadArticlesPartenaires(): Response
    {
        $categorie = $this->categoriesArticleRepository->findOneBy(['nom' => 'Partenaires']);
        $categorieId = $categorie->getId();
        // On va sur la route qui affiche les articles selon la catégorie passée en id, et c'est sur l'autre controller que s'effectue la logique
        return $this->redirectToRoute('categorie.articles', ['idCategorie' => $categorieId]);
    }
}

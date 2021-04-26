<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EventsRepository;
use App\Repository\ArticlesRepository;
use App\Repository\CategoriesArticleRepository;
use App\Service\TodayGenerator;

class HomeController extends AbstractController
{
    public function __construct(
        EventsRepository $eventsRepository,
        ArticlesRepository $articlesRepository,
        CategoriesArticleRepository $categoriesArticleRepository
    ) {
        $this->eventsRepository = $eventsRepository;
        $this->articlesRepository = $articlesRepository;
        $this->categoriesArticleRepository = $categoriesArticleRepository;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(TodayGenerator $todayGenerator): Response
    {
        // On récupère la date du jour, que l'on peut changer dans la classe
        $today = $todayGenerator->generateAToday();
        // On récupère les 3 derniers communiqués, dont en priorité, les épinglés
        $communiques = $this->getCommuniques();
        // On récupère les 3 events à venir
        $eventsToCome = $this->eventsRepository->findActifEventsToCome($today);
        // On récupère le nbre total d'events futurs
        $countTotalEventsToCome = $this->eventsRepository->countTotalActifEventsToCome($today);
        // On récupère le nbre total d'events passés
        $countTotalCompletedEvents = $this->eventsRepository->countTotalActifCompletedEvents($today);
        // On récupère en tout 3 articles, dont en priorité, les épinglés
        $articles = $this->getArticles();

        return $this->render('home.html.twig', [
            'menu_courant' => 'home',
            'communiques' => $communiques,
            'events' => $eventsToCome,
            'epoque' => 'futur',
            'today' => $today,
            'countTotalEventsToCome' => $countTotalEventsToCome,
            'countTotalCompletedEvents' => $countTotalCompletedEvents,
            'articles' => $articles,
        ]);
    }


    /**
     * On récupère les 3 derniers articles actifs dans la catégorie Communiqués, dont en priorité les épinglés s'il y en a.
     */
    private function getCommuniques()
    {
        $categorie = $this->categoriesArticleRepository->findOneBy(['nom' => 'Communiqués']);
        $pinnedCommuniques = $this->articlesRepository->findBy(
            [
                'categories_article' => $categorie,
                'actif' => true,
                'listed' => true,
                'epingle' => true,
            ],
            ['created_at' => "DESC"],
            3
        );
        $nonPinnedCommuniques = [];
        if (count($pinnedCommuniques) < 3) {
            // On complète avec des communiqués non-épinglés
            $nonPinnedCommuniques = $this->articlesRepository->findBy(
                [
                    'categories_article' => $categorie,
                    'actif' => true,
                    'listed' => true,
                    'epingle' => false,
                ],
                ['created_at' => "DESC"],
                3 - count($pinnedCommuniques)
            );
        }
        // On fusionne les deux tableaux $pinnedCommuniques et $nonPinnedCommuniques dans $communiques.
        $communiques = array_merge($pinnedCommuniques, $nonPinnedCommuniques);
        return $communiques;
    }


    /**
     * On récupère les 3 derniers articles actifs et listés
     */
    private function getArticles()
    {

        $articles = $this->articlesRepository->findBy(
            [
                'actif' => true,
                'listed' => true,
            ],
            ['created_at' => "DESC"],
            3
        );
        return
            $articles;
    }
}

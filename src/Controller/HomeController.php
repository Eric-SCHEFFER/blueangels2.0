<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EventsRepository;
use App\Repository\ArticlesRepository;
use App\Service\TodayGenerator;

class HomeController extends AbstractController
{
    public function __construct(
        EventsRepository $eventsRepository,
        ArticlesRepository $articlesRepository
    ) {
        $this->eventsRepository = $eventsRepository;
        $this->articlesRepository = $articlesRepository;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(TodayGenerator $todayGenerator): Response
    {
        // On récupère la date du jour, que l'on peut changer dans la classe
        $today = $todayGenerator->generateAToday();
        // On récupère les 3 events à venir
        $eventsToCome = $this->eventsRepository->findEventsToCome($today);
        // On récupère le nbre total d'events futurs
        $countTotalEventsToCome = $this->eventsRepository->countTotalEventsToCome($today);
        // On récupère le nbre total d'events passés
        $countTotalCompletedEvents = $this->eventsRepository->countTotalCompletedEvents($today);
        // On récupère en tout 3 articles, dont en premier, les épinglés s'il y en a
        $articles = $this->getArticles();

        return $this->render('home.html.twig', [
            'menu_courant' => 'home',
            'eventsToCome' => $eventsToCome,
            'today' => $today,
            'countTotalEventsToCome' => $countTotalEventsToCome,
            'countTotalCompletedEvents' => $countTotalCompletedEvents,
            'articles' => $articles,
        ]);
    }


    /**
     * On récupère 3 articles, dont en priorité des épinglés s'il y en a.
     */
    private function getArticles()
    {
        // Articles épinglés
        $pinnedArticles = $this->articlesRepository->findPinnedArticles();
        $nonPinnedArticles = [];
        if (count($pinnedArticles) < 3) {
            // On complète avec des articles non-épinglés
            $nonPinnedArticles = $this->articlesRepository->findArticles(3 - count($pinnedArticles));
        }
        // On fusionne les deux tableaux $pinnedArticles et $nonPinnedArticles dans $articles.
        $articles = array_merge($pinnedArticles, $nonPinnedArticles);
        return
            $articles;
    }
}

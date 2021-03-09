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
        // On récupère en tout 3 articles, dont en priorité des épinglés s'il y en a
        $articles = $this->getArticles();

        return $this->render('home.html.twig', [
            'menu_courant' => 'home',
            'eventsToCome' => $eventsToCome,
            'today' => $today,
            'countTotalEventsToCome' => $countTotalEventsToCome,
            'countTotalCompletedEvents' => $countTotalCompletedEvents,
            'pinnedArticles' => $articles[0],
            'nonPinnedArticles' => $articles[1],
        ]);
    }


    /**
     * S'il y a moins de 3 articles pinned trouvés, on complète avec des articles non pinned
     */
    private function getArticles()
    {
        $pinnedArticles = $this->articlesRepository->findPinnedArticles();
        $nonPinnedArticles = [];
        if (count($pinnedArticles) < 3) {
            $nonPinnedArticles = $this->articlesRepository->findArticles(3 - count($pinnedArticles));
        }
        return [
            $pinnedArticles,
            $nonPinnedArticles,
        ];
    }




    // On recupère jusqu'à 3 events futurs
    // $countEventsToCome = nombre d'évènements récupérés
    // S'il y a moins de 3 events futurs (Si $countEventsToCome<3), on récupère aussi 3-$countEventsToCome évènements passés
    // private function getEvents($today)
    // {
    //     $eventsToCome = $this->eventsRepository->findEventsToCome($today);
    //     $completedEvents = [];
    //     $countEventsToCome = count($eventsToCome);
    //     if ($countEventsToCome < 3) {
    //         $completedEvents = $this->eventsRepository->findCompletedEvents($today, 3 - $countEventsToCome);
    //     }
    //     return [
    //         $eventsToCome,
    //         $completedEvents,
    //     ];
    // }
}

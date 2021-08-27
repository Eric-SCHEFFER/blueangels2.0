<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EventsRepository;
use App\Service\TodayGenerator;

class EventsToComeController extends AbstractController
{
    public function __construct(
        EventsRepository $eventsRepository
    ) {
        $this->eventsRepository = $eventsRepository;
    }

    /**
     * @Route("/a_venir", name="a_venir")
     */
    public function index(TodayGenerator $todayGenerator): Response
    {
        // On récupère la date du jour, que l'on peut changer dans cette classe
        $today = $todayGenerator->generateAToday();
        // On récupère tous les events futurs
        $events = $this->getEvents($today);
        // On récupère le nbre total d'events futurs
        $countTotalEventsToCome = $this->eventsRepository->countTotalActifEventsToCome($today);
        // On récupère le nbre total d'events passés
        $countTotalCompletedEvents = $this->eventsRepository->countTotalActifCompletedEvents($today);

        return $this->render('events/eventsToCome.html.twig', [
            'menu_courant' => 'events',
            'events' => $events,
            'epoque' => 'futur',
            'today' => $today,
            'countTotalCompletedEvents' => $countTotalCompletedEvents,
            'countTotalEventsToCome' => $countTotalEventsToCome,
        ]);
    }

    /**
     * On récupère les derniers events actifs, avec en priorité, ceux épinglés s'il y en a.
     * On utilise les fonctions findActifPinnedEventsToCome et findActifNonPinnedEventsToCome depuis le repo.
     */
    private function getEvents($today)
    {
        $pinnedEvents = $this->eventsRepository->findActifPinnedEventsToCome($today, null);
        $nonPinnedEvents = [];
        // On complète avec des events non-épinglés
        $nonPinnedEvents = $this->eventsRepository->findActifNonPinnedEventsToCome($today, null);
        // dd($nonPinnedEvents);
        // On fusionne $pinnedEvents et $nonPinnedEvents
        $events = array_merge($pinnedEvents, $nonPinnedEvents);
        return $events;
    }
}

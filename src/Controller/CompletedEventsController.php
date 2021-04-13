<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EventsRepository;
use App\Service\TodayGenerator;

class CompletedEventsController extends AbstractController
{
    public function __construct(
        EventsRepository $eventsRepository
    ) {
        $this->eventsRepository = $eventsRepository;
    }

    /**
     * @Route("/completed_events", name="completed_events")
     */
    public function index(TodayGenerator $todayGenerator): Response
    {
        // On récupère la date du jour, que l'on peut changer dans la classe
        $today = $todayGenerator->generateAToday();
        // On récupère le nbre total d'events passés actifs
        $countTotalActifCompletedEvents = $this->eventsRepository->countTotalActifCompletedEvents($today);
        // On récupère tous les events passés activés
        $actifCompletedEvents = $this->eventsRepository->findAllActifCompletedEvents($today);
        
        return $this->render('completed_events/index.html.twig', [
            'menu_courant' => 'completedEvents',
            'today' => $today,
            'countTotalActifCompletedEvents' => $countTotalActifCompletedEvents,
            'actifCompletedEvents' => $actifCompletedEvents,
        ]);
    }
}




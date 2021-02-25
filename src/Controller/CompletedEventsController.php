<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EventsRepository;
use DateTime;

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
    public function index(): Response
    {
        $today = new DateTime('2021-01-05'); // Pour tester d'autres dates du jour
        // On récupère le nbre total d'events passés
        $countTotalCompletedEvents = $this->eventsRepository->countTotalCompletedEvents($today);
        // On récupère tous les events passés
        $completedEvents = $this->eventsRepository->findAllCompletedEvents($today);
        
        return $this->render('completed_events/index.html.twig', [
            'menu_courant' => 'completedEvents',
            'today' => $today,
            'countTotalCompletedEvents' => $countTotalCompletedEvents,
            'completedEvents' => $completedEvents,
        ]);
    }
}




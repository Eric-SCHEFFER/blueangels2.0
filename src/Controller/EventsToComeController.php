<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EventsRepository;
use DateTime;

class EventsToComeController extends AbstractController
{
    public function __construct(
        EventsRepository $eventsRepository
    ) {
        $this->eventsRepository = $eventsRepository;
    }

    /**
     * @Route("/events_to_come", name="events_to_come")
     */
    public function index(): Response
    {
        $today = new DateTime('2021-01-20'); // Pour tester d'autres dates du jour
        // On récupère tous les events futurs
        $events = $this->eventsRepository->findAllEventsToCome($today);
        // On récupère le nbre total d'events futurs
        $countTotalEventsToCome = $this->eventsRepository->countTotalEventsToCome($today);
        
        return $this->render('EventsToCome/index.html.twig', [
            'menu_courant' => 'events',
            'eventsToCome' => $events,
            'today' => $today,
            'countTotalEventsToCome' => $countTotalEventsToCome,
        ]);
    }
}




<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EventsRepository;
use DateTime;

class HomeController extends AbstractController
{
    public function __construct(EventsRepository $eventsRepository)
    {
        $this->eventsRepository = $eventsRepository;
    }

    /**
     * @Route("/home", name="home")
     */
    public function index(): Response
    {
        // On recupère jusqu'à 3 events futurs
        // $countEventsToCome = nombre d'évènements récupérés
        // S'il y a moins de 3 events futurs (Si $countEventsToCome<3), on récupère aussi 3-$countEventsToCome évènements passés
        $date  = mktime(0, 0, 0, date("m")  , date("d")+1, date("Y")); // Pour tester une autre date
        $today = date('Y-m-d',$date);
        $eventsToCome = $this->eventsRepository->findEventsToCome($today);
        $completedEvents = [];
        $countEventsToCome = count($eventsToCome);
        if ($countEventsToCome < 3) {
            $completedEvents = $this->eventsRepository->findCompletedEvents($today, 3 - $countEventsToCome);
        }
        return $this->render('home.html.twig', [
            'eventsToCome' => $eventsToCome,
            'completedEvents' => $completedEvents,
        ]);
    }
}

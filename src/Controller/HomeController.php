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
        // On recupère les 3 events futurs, ou moins s'il y en a moins 
        // N = nombre d'évènements récupérés
        // S'il y a moins de 3 events futurs (Si N<3), on récupère aussi 3-N évènements passés
        // $date  = mktime(0, 0, 0, date("m")  , date("d")-13, date("Y")); // Pour tester une autre date
        $today = date('Y-m-d');
        $eventsToCome = $this->eventsRepository->findEventsToCome($today);
        $completedEvents = [];
        $nbEventsToCome = count($eventsToCome);
        if ($nbEventsToCome < 3) {
            $completedEvents = $this->eventsRepository->findCompletedEvents($today, 3 - $nbEventsToCome);
        }
        return $this->render('home.html.twig', [
            'controller_name' => 'HomeController',
            'eventsToCome' => $eventsToCome,
            'completedEvents' => $completedEvents,
        ]);
    }
}

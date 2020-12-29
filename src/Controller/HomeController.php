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
        

        // TODO: À partir de DateTime actuelle:
        // On recupère les 3 events futurs triés en ASC, 
        // N = nombre d'évènements récupérés
        // Si N<3, on récupère aussi 3-N évènements passés, triés en DESC
        //$next3Events = $this->eventsRepository->findNext3Events($maintenant);
        $maintenant = new DateTime();
        $lastEvents = $this->eventsRepository->findLastEvents($maintenant, '1');
        dd($lastEvents);
        return $this->render('home.html.twig', [
            'controller_name' => 'HomeController',
            //'next3Events' => $next3Events,
            'lastEvents' => $lastEvents,
        ]);
    }
}

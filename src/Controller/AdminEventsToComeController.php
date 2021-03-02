<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EventsRepository;
use App\Service\TodayGenerator;

class AdminEventsToComeController extends AbstractController
{
    public function __construct(
        EventsRepository $eventsRepository
    ) {
        $this->eventsRepository = $eventsRepository;
    }

    /**
     * @Route("/admin/evenements_a_venir", name="admin_events_a_venir")
     */
    public function index(TodayGenerator $todayGenerator): Response
    {
        // On récupère la date du jour, que l'on peut changer dans cette classe
        $today = $todayGenerator->generateAToday();
        // On récupère tous les events futurs
        $events = $this->eventsRepository->findAllEventsToCome($today);
        // On récupère le nbre total d'events futurs
        $countTotalEventsToCome = $this->eventsRepository->countTotalEventsToCome($today);
        
        return $this->render('admin/adminEventsToCome.html.twig', [
            'menu_courant' => 'events',
            'eventsToCome' => $events,
            'today' => $today,
            'countTotalEventsToCome' => $countTotalEventsToCome,
        ]);
    }
}




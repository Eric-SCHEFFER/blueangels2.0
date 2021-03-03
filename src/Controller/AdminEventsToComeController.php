<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Events;
use App\Repository\EventsRepository;
use App\Service\TodayGenerator;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EventType;

class AdminEventsToComeController extends AbstractController
{
    public function __construct(
        EventsRepository $eventsRepository
    ) {
        $this->eventsRepository = $eventsRepository;
    }

    /**
     * @Route("/admin/evenements_a_venir", name="admin.events_a_venir", methods={"GET"})
     */
    public function index(TodayGenerator $todayGenerator): Response
    {
        // On récupère la date du jour, que l'on peut changer dans cette classe
        $today = $todayGenerator->generateAToday();
        // On récupère tous les events futurs
        $events = $this->eventsRepository->findAllEventsToCome($today);
        // On récupère le nbre total d'events futurs
        $countTotalEventsToCome = $this->eventsRepository->countTotalEventsToCome($today);
        
        return $this->render('admin/events/adminEventsToCome.html.twig', [
            'eventsToCome' => $events,
            'today' => $today,
            'countTotalEventsToCome' => $countTotalEventsToCome,
        ]);
    }



    // ======== ÉDITER UN ÉVÈNEMENT ========
    /**
     * @Route("/admin/events/edit/{id}", name="admin.events.avenir.edit", methods="GET|POST")
     * @param Events $events
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Events $event, Request $request)
    {
        return $this->render('admin/events/edit.html.twig', [
            
           
        ]);
    }
}




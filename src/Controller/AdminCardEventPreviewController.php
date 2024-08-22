<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EventsRepository;
use App\Service\TodayGenerator;

class AdminCardEventPreviewController extends AbstractController
{
    public function __construct(
        EventsRepository $eventsRepository
    ) {
        $this->eventsRepository = $eventsRepository;
    }

    #[Route('admin/preview/card_event/{id}', name: 'admin.preview.card.event')]
    public function loadEvent($id, EventsRepository $eventsRepository, TodayGenerator $todayGenerator): Response
    {
        $event = $this->eventsRepository->find($id);

        // On récupère la date du jour, que l'on peut changer dans la classe
        $today = $todayGenerator->generateAToday();

        // On determine si l'event est futur ou passé
        if ($event->getDateEvent()->format('Y-m-d') >= $today->format('Y-m-d')) {
            $epoque = "futur";
        } else
            $epoque = "passe";

        return $this->render('admin/preview/cardEvent.html.twig', [
            'event' => $event,
            'epoque' => $epoque,
            'today' => $today
        ]);
    }
}

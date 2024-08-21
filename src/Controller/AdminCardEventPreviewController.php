<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EventsRepository;

class AdminCardEventPreviewController extends AbstractController
{
    public function __construct(
        EventsRepository $eventsRepository
    ) {
        $this->eventsRepository = $eventsRepository;
    }

    #[Route('admin/preview/card_event/{id}', name: 'admin.preview.card.event')]
    public function loadEvent($id, EventsRepository $eventsRepository): Response
    {
        $event = $this->eventsRepository->find($id);
        return $this->render('admin/preview/cardEvent.html.twig', [
            'event' => $event,
        ]);
    }
}

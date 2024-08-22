<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EventsRepository;
use App\Repository\ImagesEventRepository;
use App\Service\TodayGenerator;



class AdminFullPageEventPreviewController extends AbstractController
{
    public function __construct(
        EventsRepository $eventsRepository,
        ImagesEventRepository $imagesEventRepository
    ) {
        $this->eventsRepository = $eventsRepository;
        $this->imagesEventRepository = $imagesEventRepository;
    }

    #[Route('admin/preview/fullPageEvent/{id}', name: 'admin.preview.fullPageEvent')]
    public function loadEvent($id, EventsRepository $eventsRepository, TodayGenerator $todayGenerator): Response
    {
        $event = $this->eventsRepository->find($id);
        $images = $this->imagesEventRepository->findBy(['event' => $event]);

        // On récupère la date du jour, que l'on peut changer dans la classe
        $today = $todayGenerator->generateAToday();

        // On determine si l'event est futur ou passé
        if ($event->getDateEvent()->format('Y-m-d') >= $today->format('Y-m-d')) {
            $epoque = "futur";
        } else
            $epoque = "passe";

        return $this->render('admin/preview/fullPageEvent.html.twig', [
            'event' => $event,
            'images' => $images,
            'epoque' => $epoque,
            'today' => $today,
        ]);
    }
}

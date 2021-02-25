<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Events;
use App\Repository\ImagesEventRepository;
use App\Service\TodayGenerator;
use DateTime;

class EventController extends AbstractController
{
    /**
     * @Route("/event/{id}", name="event_")
     */
    public function eventLoad($id, TodayGenerator $todayGenerator, ImagesEventRepository $imagesEventRepository): Response
    {
        // On récupère la date du jour, que l'on peut changer dans cette classe
        $today = $todayGenerator->generateAToday();
        $repo = $this->getDoctrine()->getRepository(Events::class);
        $event = $repo->find($id);
        $images = $imagesEventRepository->findBy(['event' => $event]);
        return $this->render('event/index.html.twig', [
            'event' => $event,
            'images' => $images,
            'today' => $today,
        ]);
    }
}

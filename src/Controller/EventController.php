<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Events;
use App\Repository\ImagesEventRepository;
use App\Service\TodayGenerator;

class EventController extends AbstractController
{
    /**
     * @Route("/event/{id}", name="event_")
     */
    public function eventLoad($id, TodayGenerator $todayGenerator, ImagesEventRepository $imagesEventRepository): Response
    {
        // On récupère la date du jour, que l'on peut changer dans la classe
        $today = $todayGenerator->generateAToday();
        $repo = $this->getDoctrine()->getRepository(Events::class);

        // On récupère l'event par son id, et seulement s'il est actif
        $event = $repo->findOneBy(
            [
                'id' => $id,
                'actif' => true,
            ]

        );

        // On determine si l'event est futur ou passé
        if ($event->getDateEvent()->format('Y-m-d') >= $today->format('Y-m-d')) {
            $epoque = "futur";
        }
        else
        $epoque = "passe";

        if (empty($event)) {
            // Erreur 404
            throw $this->createNotFoundException('L\'évènement n\'est pas actif');
        }

        $images = $imagesEventRepository->findBy(['event' => $event]);
        return $this->render('event/index.html.twig', [
            'event' => $event,
            'images' => $images,
            'today' => $today,
            'epoque' => $epoque,
        ]);
    }
}

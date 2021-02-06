<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Events;
use App\Repository\ImagesEventRepository;


class EventController extends AbstractController
{
    /**
     * @Route("/event/{id}", name="event_")
     */
    public function eventLoad($id, ImagesEventRepository $imagesEventRepository): Response
    {
        $repo = $this->getDoctrine()->getRepository(Events::class);
        $event = $repo->find($id);
        $images = $imagesEventRepository->findBy(['event' => $event]);
        return $this->render('event/index.html.twig', [
            'event' => $event,
            'images' => $images,
        ]);
    }
}

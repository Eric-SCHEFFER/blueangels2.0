<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Events;
use App\Entity\ImagesEvent;
use App\Repository\EventsRepository;
use App\Repository\ImagesEventRepository;
use App\Service\TodayGenerator;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EventType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdminCloneEventController extends AbstractController
{
    public function __construct(
        EventsRepository $eventsRepository,
        EntityManagerInterface $em
    ) {
        $this->eventsRepository = $eventsRepository;
        $this->em = $em;
    }


    /**
     * Méthode pour cloner un évènement et leurs images, sans les recréer sur le serveur
     * @Route("/admin/events/clone/{id}", name="admin.events.clone", methods="GET|POST")
     */
    public function cloneEvent(Events $event, Request $request, ImagesEventRepository $imagesEventRepository)
    {
        // On créé une instance clonée de event
        $images = $imagesEventRepository->findBy(['event' => $event->getId()]);
        $eventClone = clone $event;

        // On rajoute "clone" avant le nom, juste différencier lors de la 1ère edition de l'event cloné
        $eventClone->setNom('[clone] ' . $eventClone->getNom());
        $eventClone->setActif(0);
        $this->em->persist($eventClone);
        $this->em->flush();

        // On utilise les mêmes images physiques sur le serveur. On recopie donc les noms des images de l'event d'origine vers le nouveau
        // On a aussi modifié la logique de suppression des images physiques dans AdminEventsController (Ne supprimer les images physiques que s'il elles ne sont plus utilisées par aucun event)
        foreach ($images as $image) {
            $imagesEvent = new ImagesEvent;
            $imagesEvent->setEvent($eventClone);
            $imagesEvent->setNom($image->getNom());
            $this->em->persist($imagesEvent);
        }
        $this->em->flush();

        $this->addFlash('succes', 'Nouvel évènement créé à partir de "' . $event->getNom() . '".');
        return $this->redirectToRoute('admin.events.edit', ['id' => $eventClone->getId()]);
    }
}

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




    // TODO: Méthode pour cloner un évènement

    /**
     * @Route("/admin/events/clone/{id}", name="admin.events.clone", methods="GET|POST")
     */
    public function cloneEvent(Events $event, Request $request, ImagesEventRepository $imagesEventRepository)
    {
        // On créé une instance clone de event
        $eventClone = clone $event;
        // $images = $imagesEventRepository->findBy(['event' => $eventClone]);

        // On rajoute "clone" avant le nom, pour bien différencier lors de la 1ère edition de l'event cloné
        $eventClone->setNom('[clone] ' . $eventClone->getNom());
        $eventClone->setActif(0);

        // TODO: Gestion des images


        



        $this->em->persist($eventClone);
        $this->em->flush();
        $this->addFlash('succes', 'Nouvel évènement cloné avec succès depuis "' . $event->getNom() . '". (Inactif par défaut)');
        return $this->redirectToRoute('admin.events.edit', ['id' => $eventClone->getId()]);
    }
}

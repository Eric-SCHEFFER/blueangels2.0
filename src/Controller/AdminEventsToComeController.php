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
use Doctrine\ORM\EntityManagerInterface;

class AdminEventsToComeController extends AbstractController
{
    public function __construct(
        EventsRepository $eventsRepository,
        EntityManagerInterface $em
    ) {
        $this->eventsRepository = $eventsRepository;
        $this->em = $em;
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


    // ======== CRÉER UN ÉVÈNEMENT ========
    /**
     * @Route("/admin/events/nouveau", name="admin.events.avenir.nouveau")
     */
    public function new(Request $request)
    {
        $event = new Events();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($event);
            $this->em->flush();
            $this->addFlash('succes', 'Évènement créé avec succès');
            return $this->redirectToRoute('admin.events_a_venir');
        }
        return $this->render('admin/events/nouveau.html.twig', [
            'form' => $form->createView()
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
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($event);
            $this->em->flush();
            $this->addFlash('succes', 'Évènement mis à jour avec succès');
            return $this->redirectToRoute('admin.events_a_venir');
        }
        return $this->render('admin/events/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // ======== SUPPRIMER UN ÉVÈNEMENT ========
    /**
     * @Route("/admin/events/edit/{id}", name="admin.event.avenir.delete", methods={"DELETE"})
     * @param Events $events
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(Events $events, Request $request)
    {
        // On supprime la réalisation, ainsi que toutes ses images (Option orphanRemoval) dans la base
        $this->em->remove($events);
        $this->em->flush();
        $this->addFlash('succes', '"' . $events->getNom() . '"' . ' supprimé avec succès');
        //return new HttpFoundationResponse('Suppression');
        return $this->redirectToRoute('admin.events_a_venir');
    }
}

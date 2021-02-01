<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EventsRepository;
use App\Repository\ArticlesRepository;
use App\Repository\CommuniqueRepository;
use DateTime;

class HomeController extends AbstractController
{
    public function __construct(
        EventsRepository $eventsRepository,
        ArticlesRepository $articlesRepository,
        CommuniqueRepository $communiqueRepository
    ) {
        $this->eventsRepository = $eventsRepository;
        $this->articlesRepository = $articlesRepository;
        $this->communiqueRepository = $communiqueRepository;
    }

    /**
     * @Route("/home", name="home")
     */
    public function index(): Response
    {
        $today = new DateTime('2021-01-21'); // Pour tester d'autres dates du jour
        // On récupère les 3 events
        $events = $this->getEvents($today);
        // On récupère le nbre total d'events futurs
        $countTotalEventsToCome = $this->eventsRepository->countTotalEventsToCome($today);
        // On récupère les articles
        $articles = $this->articlesRepository->findBy([], ['created_at' => 'DESC'], 3, 0);
        // On récupère les communiqués
        $communiques = $this->communiqueRepository->findBy([], ['created_at' => 'DESC'], 1, 0);
        return $this->render('home.html.twig', [
            'eventsToCome' => $events[0],
            'completedEvents' => $events[1],
            'today' => $today,
            'countTotalEventsToCome' => $countTotalEventsToCome,
            'articles' => $articles,
            'communiques' => $communiques,
        ]);
    }



    // On recupère jusqu'à 3 events futurs
    // $countEventsToCome = nombre d'évènements récupérés
    // S'il y a moins de 3 events futurs (Si $countEventsToCome<3), on récupère aussi 3-$countEventsToCome évènements passés
    private function getEvents($today)
    {
        $eventsToCome = $this->eventsRepository->findEventsToCome($today);
        $completedEvents = [];
        $countEventsToCome = count($eventsToCome);
        if ($countEventsToCome < 3) {
            $completedEvents = $this->eventsRepository->findCompletedEvents($today, 3 - $countEventsToCome);
        }
        return [
            $eventsToCome,
            $completedEvents,
        ];
    }
}

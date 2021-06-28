<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EventsRepository;
use App\Repository\ArticlesRepository;
use App\Repository\CategoriesArticleRepository;
use App\Service\TodayGenerator;

class HomeController extends AbstractController
{
    public function __construct(
        EventsRepository $eventsRepository,
        ArticlesRepository $articlesRepository,
        CategoriesArticleRepository $categoriesArticleRepository
    ) {
        $this->eventsRepository = $eventsRepository;
        $this->articlesRepository = $articlesRepository;
        $this->categoriesArticleRepository = $categoriesArticleRepository;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(TodayGenerator $todayGenerator): Response
    {
        // La date du jour, récupérée dans le service generateAToday
        $today = $todayGenerator->generateAToday();
        // Les 3 derniers communiqués (dont en priorité, les épinglés)
        $communiques = $this->getCommuniques();
        // Les 3 events à venir (dont en priorité, les épinglés)
        $eventsToCome = $this->get3Events($today);
        // dd($eventsToCome);
        // Nbre total d'events futurs
        $countTotalEventsToCome = $this->eventsRepository->countTotalActifEventsToCome($today);
        // Nbre total d'events passés
        $countTotalCompletedEvents = $this->eventsRepository->countTotalActifCompletedEvents($today);
        // Les 3 derniers articles
        $articles = $this->getArticles();

        return $this->render('home.html.twig', [
            'menu_courant' => 'home',
            'communiques' => $communiques,
            'events' => $eventsToCome,
            'epoque' => 'futur',
            'today' => $today,
            'countTotalEventsToCome' => $countTotalEventsToCome,
            'countTotalCompletedEvents' => $countTotalCompletedEvents,
            'articles' => $articles,
        ]);
    }


    /**
     * On récupère les 3 derniers articles actifs dans la catégorie Communiqués, dont en priorité les épinglés s'il y en a.
     */
    private function getCommuniques()
    {
        $categorie = $this->categoriesArticleRepository->findOneBy(['nom' => 'Communiqués']);
        $pinnedCommuniques = $this->articlesRepository->findBy(
            [
                'categories_article' => $categorie,
                'actif' => true,
                'listed' => true,
                'epingle' => true,
            ],
            ['created_at' => "DESC"],
            3
        );
        $nonPinnedCommuniques = [];
        if (count($pinnedCommuniques) < 3) {
            // On complète avec des communiqués non-épinglés
            $nonPinnedCommuniques = $this->articlesRepository->findBy(
                [
                    'categories_article' => $categorie,
                    'actif' => true,
                    'listed' => true,
                    'epingle' => false,
                ],
                ['created_at' => "DESC"],
                3 - count($pinnedCommuniques)
            );
        }
        // On fusionne les deux tableaux $pinnedCommuniques et $nonPinnedCommuniques dans $communiques.
        $communiques = array_merge($pinnedCommuniques, $nonPinnedCommuniques);
        return $communiques;
    }


    /**
     * TODO: On récupère les 3 derniers events actifs, avec en priorité, ceux épinglés s'il y en a.
     * On utilise les fonctions find3ActifPinnedEventsToCome et find3ActifNonPinnedEventsToCome depuis le repo.
     */
    private function get3Events($today)
    {
        $pinnedEvents = $this->eventsRepository->find3ActifPinnedEventsToCome($today);
        $nonPinnedEvents = [];
        if (count($pinnedEvents) < 3) {
            // On complète avec des events non-épinglés
            $combien = 3 - count($pinnedEvents);
            $nonPinnedEvents = $this->eventsRepository->find3ActifNonPinnedEventsToCome($today, $combien);
            // dd($nonPinnedEvents);
        }
        // On fusionne $pinnedEvents et $nonPinnedEvents
        $eventsToCome = array_merge($pinnedEvents, $nonPinnedEvents);
        return $eventsToCome;
    }



    /**
     * On récupère les 3 derniers articles actifs et listés
     */
    private function getArticles()
    {

        $articles = $this->articlesRepository->findBy(
            [
                'actif' => true,
                'listed' => true,
            ],
            ['created_at' => "DESC"],
            3
        );
        return
            $articles;
    }
}

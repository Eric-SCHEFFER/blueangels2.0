<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EventsRepository;
use App\Repository\ArticlesRepository;
use App\Repository\CategoriesArticleRepository;
use App\Service\TodayGenerator;
use App\Repository\OptionsSiteRepository;

class HomeController extends AbstractController
{
    public function __construct(
        EventsRepository $eventsRepository,
        ArticlesRepository $articlesRepository,
        CategoriesArticleRepository $categoriesArticleRepository,
        OptionsSiteRepository $optionsSiteRepository
    ) {
        $this->eventsRepository = $eventsRepository;
        $this->articlesRepository = $articlesRepository;
        $this->categoriesArticleRepository = $categoriesArticleRepository;
        $this->optionsSiteRepository = $optionsSiteRepository;
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
        // Nbre total d'events futurs
        $countTotalEventsToCome = $this->eventsRepository->countTotalActifEventsToCome($today);
        // Nbre total d'events passés
        $countTotalCompletedEvents = $this->eventsRepository->countTotalActifCompletedEvents($today);
        // Les 3 derniers articles
        $articles = $this->getArticles();
        $afficherBarreCommunique = $this->optionsSiteRepository->findOneBy(['nom' => 'barre communiqués homepage']);

        return $this->render('home.html.twig', [
            'menu_courant' => 'home',
            'communiques' => $communiques,
            'events' => $eventsToCome,
            'epoque' => 'futur',
            'today' => $today,
            'countTotalEventsToCome' => $countTotalEventsToCome,
            'countTotalCompletedEvents' => $countTotalCompletedEvents,
            'articles' => $articles,
            'afficherBarreCommunique' => $afficherBarreCommunique,
        ]);
    }



    // BARRE COMMUNIQUÉS

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



    // ÉVÈNEMENTS

    /**
     * On récupère les 3 derniers events actifs, avec en priorité, ceux épinglés s'il y en a.
     * On utilise les méthodes findActifPinnedEventsToCome et findActifNonPinnedEventsToCome depuis le repo.
     */
    private function get3Events($today)
    {
        $pinnedEvents = $this->eventsRepository->findActifPinnedEventsToCome($today, 3);
        $nonPinnedEvents = [];
        if (count($pinnedEvents) < 3) {
            // On complète avec des events non-épinglés
            $combien = 3 - count($pinnedEvents);
            $nonPinnedEvents = $this->eventsRepository->findActifNonPinnedEventsToCome($today, $combien);
            // dd($nonPinnedEvents);
        }
        // On fusionne $pinnedEvents et $nonPinnedEvents
        $eventsToCome = array_merge($pinnedEvents, $nonPinnedEvents);
        return $eventsToCome;
    }



    // ARTICLES

    /**
     * On récupère les 3 derniers articles avec les épinglés en premier
     */
    private function getArticles()
    {
        $pinnedArticles = $this->articlesRepository->findPinnedActifsListedArticles(3);
        $nonPinnedArticles = [];
        if (count($pinnedArticles) < 3) {
            // On complète avec des articles non-épinglés
            $combien = 3 - count($pinnedArticles);
            $nonPinnedArticles = $this->articlesRepository->findActifsListedArticles($combien);
            // On fusionne $pinnedArticles et $nonPinnedArticless
            $articles = array_merge($pinnedArticles, $nonPinnedArticles);
        } else {
            $articles = $pinnedArticles;
        }
        return
            $articles;
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EventsRepository;
use App\Service\TodayGenerator;

class HeaderPinnedEventController extends AbstractController
{



    public function coucou(): Response
    {

        return $this->render('components/_notificationLastPinnedEvent.html.twig', [
            'coucou' => 'Ah que coucou',
        ]);
    }
}

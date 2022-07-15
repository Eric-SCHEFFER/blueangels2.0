<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\MembresAsso;

class Trombinoscope422610Controller extends AbstractController
{
    /**
     * @Route("/trombinoscope422610", name="trombinoscope422610")
     */
    public function loadTrombinoscopes(): Response
    {
        $membres = $this->getDoctrine()->getRepository(MembresAsso::class)->findby(
            ['actif' => true],
        );
        return $this->render('trombinoscope/trombinoscope422610.html.twig', [
            'membres' => $membres,
        ]);
    }
}

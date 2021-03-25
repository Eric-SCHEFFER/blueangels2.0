<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\MembresAsso;

class TrombinoscopeController extends AbstractController
{
    /**
     * @Route("/trombinoscope", name="trombinoscope")
     */
    public function loadTrombinoscopes(): Response
    {
        $membres = $this->getDoctrine()->getRepository(MembresAsso::class)->findAll();
        return $this->render('trombinoscope/trombinoscope.html.twig', [
            'membres' => $membres,
        ]);
    }
}

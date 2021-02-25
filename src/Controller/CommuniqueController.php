<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Communique;
use DateTime;

class CommuniqueController extends AbstractController
{
    /**
     * @Route("/communique/{id}", name="communique")
     */
    public function communiqueLoad($id): Response
    {
        $repo = $this->getDoctrine()->getRepository(Communique::class);
        $communique = $repo->find($id);
        return $this->render('communique/index.html.twig', [
            'communique' => $communique,
        ]);
    }
}

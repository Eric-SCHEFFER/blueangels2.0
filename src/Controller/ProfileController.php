<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function load(): Response
    {
        $contenuProfile = 'Contenu de la page profile';
        return $this->render('profile/profile.html.twig', [
            'contenuProfile' => $contenuProfile,
        ]);
    }
}

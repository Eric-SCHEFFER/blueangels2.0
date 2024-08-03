<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUtilisateursController extends AbstractController
{
    #[Route('/admin/utilisateurs', name: 'admin.utilisateurs')]
    public function index(): Response
    {
        return $this->render('admin/utilisateurs/index.html.twig', [
            'controller_name' => 'AdminUtilisateursController',
        ]);
    }
}

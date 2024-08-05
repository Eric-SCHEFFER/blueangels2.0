<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;

class AdminUtilisateursController extends AbstractController
{
    #[Route('/admin/utilisateurs', name: 'admin.utilisateurs')]
    public function loadUsers(UserRepository $users)
    {
        return $this->render('admin/utilisateurs/index.html.twig', [
            'users' => $users->findAll()
        ]);
    }
}

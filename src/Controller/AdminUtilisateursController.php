<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Form\ModifUtilisateurType;
use Symfony\Component\HttpFoundation\Request;


class AdminUtilisateursController extends AbstractController
{
    #[Route('/admin/utilisateurs', name: 'admin.utilisateurs')]
    public function loadUsers(UserRepository $users)
    {
        return $this->render('admin/utilisateurs/index.html.twig', [
            'users' => $users->findAll()
        ]);
    }

    # Modifier le rôle d'un utilisateur
    #[Route('/admin/utilisateurs/editUserRole/{id}', name: 'admin.utilisateurs.editUserRole')]
    public function editUser(User $user, Request $request)
    {
        // L'utilisateur connecté ne doit pas pouvoir acceder à cette page pour modifier son propre rôle
        if ($this->getUser()->getEmail() == $user->getEmail()) {
            return $this->redirectToRoute('admin.utilisateurs');
        }

        $form = $this->createForm(ModifUtilisateurType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('succes', 'Utilisateur modifié avec succès');
            return $this->redirectToRoute('admin.utilisateurs');
        }

        return $this->render('admin/utilisateurs/editUserRole.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

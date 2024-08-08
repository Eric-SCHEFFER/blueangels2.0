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

    // Suppression d'un user
    #[Route('/admin/utilisateurs/delete/{id}', name: 'admin.utilisateurs.delete', methods: ['DELETE'])]
    public function delete(User $user, Request $request)
    {
        // L'utilisateur connecté ne doit pas pouvoir acceder à cette page pour modifier son propre rôle
        if ($this->getUser()->getEmail() == $user->getEmail()) {
            return $this->redirectToRoute('admin.utilisateurs');
        }

        // On vérifie le token pour sécuriser la suppression d'un user
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->get('_token'))) {
            // On supprime l'user
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('succes', '"' . $user->getEmail() . '"' . ' supprimé avec succès');
        }
        return $this->redirectToRoute('admin.utilisateurs');
    }
}

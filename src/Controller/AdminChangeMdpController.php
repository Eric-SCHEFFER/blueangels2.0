<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class AdminChangeMdpController extends AbstractController
{
    /**
     * @Route("profile/changeMdp", name="profile.change.mdp")
     */
    public function ChangeMdp(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            $err = false;

            // ======== Série de tests de validité des mots de passe entrés ========

            $motDePasseAVerifier = $request->request->get('actuPassword');
            $passValide = $passwordEncoder->isPasswordValid($user, $motDePasseAVerifier);
            // Si le mot de passe actuel entré n'est pas correct
            if (!$passValide) {
                $this->addFlash('error', 'Le mot de passe actuel n\'est pas valide');
                $err = true;
            }
            // Si les 2 nouveaux mots de passe entrés ne sont pas identiques
            $nouvMotDePasse = $request->request->get('newPassword');
            if ($nouvMotDePasse !== $request->request->get('newPasswordConfirm')) {
                $this->addFlash('error', 'Les deux nouveaux mots de passe ne sont pas identiques');
                $err = true;
            }
            // Si le nouveau mdp fait moins de 8 caractères
            $nbrCar = 8;
            if (strlen($nouvMotDePasse) < $nbrCar) {
                $this->addFlash('error', 'Le nouveau mot de passe doit compter au moins ' . $nbrCar . ' caractères');
                $err = true;
            }
            // Si le nouveau mdp ne contient pas au moins une minuscule
            if (!preg_match('#[a-z]+#', $nouvMotDePasse)) {
                $this->addFlash('error', 'Le nouveau mot de passe doit contenir au moins 1 minuscule');
                $err = true;
            }
            // Si le nouveau mot de passe ne contient pas au moins une majuscule
            if (!preg_match('#[A-Z]+#', $nouvMotDePasse)) {
                $this->addFlash('error', 'Le nouveau mot de passe doit contenir au moins 1 majuscule');
                $err = true;
            }
            // Si le nouveau mot de passe ne contient pas au moins un chiffre
            if (!preg_match('#[0-9]+#', $nouvMotDePasse)) {
                $this->addFlash('error', 'Le nouveau mot de passe doit contenir au moins 1 chiffre');
                $err = true;
            }
            // Si le nouveau mot de passe ne contient pas au moins l'un des caractères spéciaux: & ) = ( ?
            if (!preg_match('#[&)=(?]+#', $nouvMotDePasse)) {
                $this->addFlash('error', 'Le nouveau mot de passe doit contenir au moins l\'un de ces caractères: & ) = ( ?');
                $err = true;
            }


            // ======== En cas d'erreur, on revient vers la page de changement de mot de passe, avec les messages d'erreurs ========
            if ($err) {
                return $this->render('profile/change_mdp/index.html.twig');
            }

            // ======== Si tout est bon, on enregistre le nouveau mot de passe dans la base ========
            else {
                $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('newPassword')));
                $em->flush();
                $this->addFlash('succes', 'Mot de passe modifié avec succès');
                return $this->redirectToRoute('profile');
            }
        }
        return $this->render('profile/change_mdp/index.html.twig');
    }
}

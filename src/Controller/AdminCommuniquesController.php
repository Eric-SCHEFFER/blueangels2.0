<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Communique;
use App\Repository\CommuniqueRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CommuniqueType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdminCommuniquesController extends AbstractController
{
    public function __construct(
        CommuniqueRepository $communiqueRepository,
        EntityManagerInterface $em
    ) {
        $this->communiqueRepository = $communiqueRepository;
        $this->em = $em;
    }

    /**
     * @Route("/admin/communiques", name="admin.communiques", methods={"GET"})
     */
    public function index(): Response
    {
        // On récupère tous les communiques
        $communiques = $this->communiqueRepository->findAll();
        return $this->render('admin/communiques/adminCommuniques.html.twig', [
            'communiques' => $communiques,
        ]);
    }


    // ======== CRÉER UN COMMUNIQUÉ ========
    /**
     * @Route("/admin/communiques/nouveau", name="admin.communiques.nouveau")
     */
    public function new(Request $request)
    {
        $communique = new Communique();
        $form = $this->createForm(CommuniqueType::class, $communique);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($communique);
            $this->em->flush();
            $this->addFlash('succes', 'Communiqué créé avec succès');
            return $this->redirectToRoute('admin.communiques');
        }
        return $this->render('admin/communiques/nouveau.html.twig', [
            'form' => $form->createView()
        ]);
    }


    // ======== ÉDITER UN COMMUNIQUÉ ========
    /**
     * @Route("/admin/communiques/edit/{id}", name="admin.communiques.edit", methods="GET|POST")
     * @param Communique $communique
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Communique $communique, Request $request)
    {
        $form = $this->createForm(CommuniqueType::class, $communique);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($communique);
            $this->em->flush();
            $this->addFlash('succes', '"' . $communique->getTitre() . '"' . ' modifié avec succès');
            return $this->redirectToRoute('admin.communiques');
        }
        return $this->render('admin/communiques/edit.html.twig', [
            'communique' => $communique,
            'form' => $form->createView()
        ]);
    }


    // ======== SUPPRIMER UN COMMUNIQUÉ ========
    /**
     * @Route("/admin/communiques/edit/{id}", name="admin.communique.delete", methods={"DELETE"})
     * @param Communique $communique
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(Communique $communique, Request $request)
    {
        // Vérif token pour sécuriser la suppression d'une réalisation
        if ($this->isCsrfTokenValid('delete' . $communique->getId(), $request->get('_token'))) {
            // On supprime le communiqué dans la base
            $this->em->remove($communique);
            $this->em->flush();
            $this->addFlash('succes', '"' . $communique->getTitre() . '"' . ' supprimé avec succès');
            //return new HttpFoundationResponse('Suppression');
        }
        return $this->redirectToRoute('admin.communiques');
    }
}

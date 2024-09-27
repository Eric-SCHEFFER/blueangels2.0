<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\MembresAsso;
use App\Form\MembreAssoType;
use App\Repository\MembresAssoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\ImageTools;

class AdminTrombinoscopeController extends AbstractController
{
    public function __construct(
        // MembresAssoRepository $membresAssoRepository,
        EntityManagerInterface $em
    ) {
        // $this->membresAssoRepository = $membresAssoRepository;
        $this->em = $em;
    }


    /**
     * @Route("/admin/trombinoscope", name="admin.trombinoscope", methods={"GET"})
     */
    public function loadMembresAsso(MembresAssoRepository $membresAssoRepository): Response
    {
        $membresAsso = $membresAssoRepository->findBy([], ['prenom' => 'ASC']);
        return $this->render('admin/trombinoscope/adminTrombinoscope.html.twig', [
            'membresAsso' => $membresAsso,
        ]);
    }


    // ======== CRÉER UN TROMBINOSCOPÉ ========
    /**
     * @Route("/admin/trombinoscope/nouveau", name="admin.trombinoscope.nouveau")
     */
    public function new(Request $request, ImageTools $imageTools)
    {
        $membreAsso = new MembresAsso();
        $form = $this->createForm(MembreAssoType::class, $membreAsso);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Si une image est passée par le formulaire
            if (!empty($form->get('photo')->getData())) {
                // On récupère l'image transmise
                $image = $form->get('photo')->getData();
                // On génère un nouveau nom de fichier
                $ext = $image->guessExtension();
                $fichier = md5(uniqid()) . '.' . $ext;
                // On copie le fichier dans le dossier uploads
                $dossierImages = $this->getParameter('dossier_images_trombinoscope');
                $image->move(
                    $dossierImages,
                    $fichier
                );
                // On créé une miniature uniquement si l'image fait plus de 300 000  octets
                $pathImage = $dossierImages . '/' . $fichier;
                if (filesize($pathImage) > 300000) {
                    // On créé une miniature du fichier image avec la methode createMiniature de la class ImageTools créee dans un service.
                    // En 3e paramètre, la largeur souhaitée en px de la miniature
                    $imageTools->createMiniature($pathImage, $pathImage, 300);
                }
                // On stocke le nom de l'image dans la base de données
                $membreAsso->setPhoto($fichier);
            }
            $this->em->persist($membreAsso);
            $this->em->flush();
            $this->addFlash('succes', 'Article créé avec succès');
            // return $this->redirect($request->request->get('referer'));
            // TODO: Sécuriser la redirection en s'assurant surant que le referer vient bien de notre site.
            // Est-ce que ça fonctionne en https ?
            return $this->redirectToRoute('admin.trombinoscope');
        }
        return $this->render('admin/trombinoscope/nouveau.html.twig', [
            'form' => $form->createView()
        ]);
    }


    // ======== ÉDITER UN TROMBINOSCOPÉ ========
    /**
     * @Route("/admin/trombinoscope/edit/{id}", name="admin.trombinoscope.edit", methods="GET|POST")
     * @param MembresAsso $membresAsso
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(MembresAsso $membresAsso, Request $request, ImageTools $imageTools)
    {
        $form = $this->createForm(MembreAssoType::class, $membresAsso);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Si le champ "photo" existe dans MembreAssoType, et s'il n'est pas vide
            if ($form->has('photo') && !empty($form->get('photo')->getData())) {
                // On récupère l'image transmise
                $image = $form->get('photo')->getData();
                // On génère un nouveau nom de fichier
                $ext = $image->guessExtension();
                $fichier = md5(uniqid()) . '.' . $ext;
                // On copie le fichier dans le dossier uploads
                $dossierImages = $this->getParameter('dossier_images_trombinoscope');
                $image->move(
                    $dossierImages,
                    $fichier
                );
                // On créé une miniature uniquement si l'image fait plus de 300 000  octets
                $pathImage = $dossierImages . '/' . $fichier;
                if (filesize($pathImage) > 300000) {
                    // On créé une miniature du fichier image avec la methode createMiniature de la class ImageTools créee dans un service.
                    // En 3e paramètre, la largeur souhaitée en px de la miniature
                    $imageTools->createMiniature($pathImage, $pathImage, 300);
                }
                // On stocke le nom de l'image dans la base de données
                $membresAsso->setPhoto($fichier);
            }

            $this->em->persist($membresAsso);
            $this->em->flush();
            $this->addFlash('succes', '"' . $membresAsso->getPrenom() . '"' . ' modifié avec succès');
            // return $this->redirect($request->request->get('referer'));
            // TODO: Sécuriser la redirection en s'assurant surant que le referer vient bien de notre site.
            // Est-ce que ça fonctionne en https ?
            return $this->redirectToRoute('admin.trombinoscope');
        }
        return $this->render('admin/trombinoscope/edit.html.twig', [
            'membresAsso' => $membresAsso,
            'form' => $form->createView()
        ]);
    }


    // ======== SUPPRIMER UN TROMBINOSCOPÉ ========
    /**
     * @Route("/admin/trombinoscope/edit/{id}", name="admin.trombinoscope.delete", methods={"DELETE"})
     * @param MembresAsso $membresAsso
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(MembresAsso $membresAsso, Request $request)
    {
        // Vérif token pour sécuriser la suppression d'une réalisation
        if ($this->isCsrfTokenValid('delete' . $membresAsso->getId(), $request->get('_token'))) {
            // On supprime l'image sur le disque
            if (!empty($membresAsso->getPhoto())) {
                unlink($this->getParameter('dossier_images_trombinoscope') . '/' . $membresAsso->getPhoto());
            }
            // On supprime le trombinoscopé dans la base
            $this->em->remove($membresAsso);
            $this->em->flush();
            $this->addFlash('succes', '"' . $membresAsso->getNom() . '"' . ' supprimé avec succès');
            //return new HttpFoundationResponse('Suppression');
        }
        return $this->redirectToRoute('admin.trombinoscope');
    }

    // ======== SUPPRIMER L'IMAGE ========
    /**
     * @route("/admin/trombinoscope/image/supprime{id}", name="admin.trombinoscope.image.delete", methods={"DELETE"})
     */
    public function deleteImage(MembresAsso $membresAsso, MembresAssoRepository $membresAssoRepository, Request $request)
    {
        $data = json_decode($request->getContent(), true);
        // On vérifie si le token est valide
        if ($this->isCsrfTokenValid('delete' . $membresAsso->getId(), $data['_token'])) {
            // On récupère le nom de l'image
            $nom = $membresAsso->getPhoto();
            // On supprime le fichier
            unlink($this->getParameter('dossier_images_trombinoscope') . '/' . $nom);
            // On supprime le nom de l'image de la base de données
            $membresAsso->setPhoto('');
            $this->em->flush();
            // On répond en json
            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }
}

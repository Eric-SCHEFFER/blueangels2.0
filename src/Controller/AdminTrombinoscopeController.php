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
        $membresAsso = $membresAssoRepository->findBy([], ['prenom' => 'DESC']);
        return $this->render('admin/trombinoscope/adminTrombinoscope.html.twig', [
            'membresAsso' => $membresAsso,
        ]);
    }


    // ======== CRÉER UN TROMBINOSCOPÉ ========
    /**
     * @Route("/admin/trombinoscope/nouveau", name="admin.trombinoscope.nouveau")
     */
    public function new(Request $request)
    {
        $membreAsso = new MembresAsso();
        $form = $this->createForm(MembreAssoType::class, $membreAsso);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
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
            // On créé une image réduite uniquement si l'image fait plus de 300 000  octets
            $pathImage = $dossierImages . '/' . $fichier;
            if (filesize($pathImage) > 300000) {
                $this->creeMiniature($pathImage, $pathImage, 300);
            }
            // On stocke le nom de l'image dans la base de données
            $membreAsso->setPhoto($fichier);
            $this->em->persist($membreAsso);
            $this->em->flush();
            $this->addFlash('succes', 'Article créé avec succès');
            // return $this->redirectToRoute('admin.articles');
            // TODO: Sécuriser la redirection en s'assurant surant que le referer vient bien de notre site.
            // Est-ce que ça fonctionne en https ?
            return $this->redirect($request->request->get('referer'));
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
    public function edit(MembresAsso $membresAsso, Request $request)
    {
        $form = $this->createForm(MembreAssoType::class, $membresAsso);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
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
            // On créé une image réduite uniquement si l'image fait plus de 300 000  octets
            $pathImage = $dossierImages . '/' . $fichier;
            if (filesize($pathImage) > 300000) {
                $this->creeMiniature($pathImage, $pathImage, 300);
            }
            // On stocke le nom de l'image dans la base de données
            $membresAsso->setPhoto($fichier);
            $this->em->persist($membresAsso);
            $this->em->flush();
            $this->addFlash('succes', 'Article créé avec succès');
            // return $this->redirectToRoute('admin.articles');
            // TODO: Sécuriser la redirection en s'assurant surant que le referer vient bien de notre site.
            // Est-ce que ça fonctionne en https ?
            return $this->redirect($request->request->get('referer'));
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
            unlink($this->getParameter('dossier_images_trombinoscope') . '/' . $membresAsso->getPhoto());
            // On supprime le trombinoscopé dans la base
            $this->em->remove($membresAsso);
            $this->em->flush();
            $this->addFlash('succes', '"' . $membresAsso->getNom() . '"' . ' supprimé avec succès');
            //return new HttpFoundationResponse('Suppression');
        }
        return $this->redirectToRoute('admin');
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


    /**
     * Crée une miniature d'une image d'un fichier jpg ou png.
     * Paramètres: 1: Chemin complet de l'image source (jpg ou png).
     * 2: Chemin complet de l'image de sortie (cible).
     * 3: Largeur souhaitée en px.
     */
    private function creeMiniature($imageSource, $imageCible, $targetWidth)
    {
        // On recupère l'extension, et on minimise les caractères
        $ext = strtolower(pathinfo($imageSource, PATHINFO_EXTENSION));
        // On stocke dans des variables les noms des fonctions à lancer plus tard, selon l'extension de l'image
        if ($ext == "jpg" || $ext == "jpeg") {
            $imagecreatefrom = "imagecreatefromjpeg";
            $imageSortie = "imagejpeg";
        } elseif ($ext == "png") {
            $imagecreatefrom = "imagecreatefrompng";
            $imageSortie = "imagepng";
        } else {
            // On retourne une erreur, car ce n'est ni une image jpg, ni png
            return "Image non valide (jpg ou png uniquement)";
        }
        $sourceSize = getimagesize($imageSource);
        $portraitMalOriente = false;
        // On détecte si une image jpg est en portrait, et si elle est mal orientée
        if ($imageSortie == "imagejpeg") {
            if (isset(exif_read_data($imageSource, 'ANY_TAG')['Orientation'])) {
                $portraitMalOriente = exif_read_data($imageSource, 'ANY_TAG')['Orientation'];
                if ($portraitMalOriente == 6 && $sourceSize[0] > $sourceSize[1]) {
                    $portraitMalOriente = true;
                } else {
                    $portraitMalOriente = false;
                }
            }
        }
        if ($portraitMalOriente) {
            $sourceWidth = $sourceSize[1];
            $sourceHeight = $sourceSize[0];
        } else {
            $sourceWidth = $sourceSize[0];
            $sourceHeight = $sourceSize[1];
        }
        // On calcule les dimensions de la miniature, et on lance les fonctions php de création de miniature
        $targetHeight = ($targetWidth / $sourceWidth) * $sourceHeight;
        $imgIn = $imagecreatefrom($imageSource);
        // On pivote l'image de 90° dans le sens horaire, si nécéssaire
        if ($portraitMalOriente) {
            $imgIn = imagerotate($imgIn, -90, 0);
        }
        $imgOut = imagecreatetruecolor($targetWidth, $targetHeight);
        imagecopyresampled($imgOut, $imgIn, 0, 0, 0, 0, $targetWidth, $targetHeight, $sourceWidth, $sourceHeight);
        $imageSortie($imgOut, $imageCible);
        return $imageCible;
    }
}

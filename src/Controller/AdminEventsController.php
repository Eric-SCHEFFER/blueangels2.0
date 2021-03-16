<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Events;
use App\Entity\ImagesEvent;
use App\Repository\EventsRepository;
use App\Service\TodayGenerator;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EventType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdminEventsController extends AbstractController
{
    public function __construct(
        EventsRepository $eventsRepository,
        EntityManagerInterface $em
    ) {
        $this->eventsRepository = $eventsRepository;
        $this->em = $em;
    }

    /**
     * Charge la page du tableau d'admin des évènements passés
     * @Route("/admin/evenements_passes", name="admin.events.passes", methods={"GET"})
     */
    public function loadCompletedEvents(TodayGenerator $todayGenerator): Response
    {
        // On récupère la date du jour, que l'on peut changer dans cette classe
        $today = $todayGenerator->generateAToday();
        // On récupère tous les events passés
        $completedEvents = $this->eventsRepository->findAllCompletedEvents($today);
        // On récupère le nbre total d'events passés
        $countTotalCompletedEvents = $this->eventsRepository->countTotalCompletedEvents($today);
        return $this->render('admin/events/adminCompletedEvents.html.twig', [
            'completedEvents' => $completedEvents,
            'today' => $today,
            'countTotalCompletedEvents' => $countTotalCompletedEvents,
        ]);
    }


    /**
     * Charge la page du tableau d'admin des évènements à venir
     * @Route("/admin/evenements_a_venir", name="admin.events_a_venir", methods={"GET"})
     */
    public function loadEventsToCome(TodayGenerator $todayGenerator): Response
    {
        // On récupère la date du jour, que l'on peut changer dans cette classe
        $today = $todayGenerator->generateAToday();
        // On récupère tous les events futurs
        $events = $this->eventsRepository->findAllEventsToCome($today);
        // On récupère le nbre total d'events futurs
        $countTotalEventsToCome = $this->eventsRepository->countTotalEventsToCome($today);
        return $this->render('admin/events/adminEventsToCome.html.twig', [
            'eventsToCome' => $events,
            'today' => $today,
            'countTotalEventsToCome' => $countTotalEventsToCome,
        ]);
    }

    // TODO: Factoriser méthodes new et edit qui sont très similaires, en une seule

    // ======== CRÉER UN ÉVÈNEMENT ========
    /**
     * @Route("/admin/events/nouveau", name="admin.events.nouveau")
     */
    public function new(Request $request)
    {
        $event = new Events();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les images transmises
            $images = $form->get('imageFile')->getData();
            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $ext = $image->guessExtension();
                $fichier = md5(uniqid()) . '.' . $ext;
                // On copie le fichier dans le dossier uploads
                $dossierImages = $this->getParameter('dossier_images_events');
                $image->move(
                    $dossierImages,
                    $fichier
                );
                $imageSource = $dossierImages . "/" . $fichier;
                $imageCible = $dossierImages . "/min_" . $fichier;
                // On créé une miniature du fichier image. En 3e paramètre, la largeur souhaitée en px de la miniature
                $this->creeMiniature($imageSource, $imageCible, 270);
                // On stocke le nom de l'image dans la base de données
                $img = new ImagesEvent();
                $img->setNom($fichier);
                $event->addImagesEvent($img);
            }
            $this->em->persist($event);
            $this->em->flush();
            $this->addFlash('succes', 'Évènement créé avec succès');
            // TODO: Sécuriser la redirection en s'assurant surant que le referer vient bien de notre site.
            // Est-ce que ça fonctionne en https ?
            return $this->redirect($request->request->get('referer'));
        }
        return $this->render('admin/events/nouveau.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // ======== ÉDITER UN ÉVÈNEMENT ========
    /**
     * @Route("/admin/events/edit/{id}", name="admin.events.edit", methods="GET|POST")
     * @param Events $events
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Events $event, Request $request)
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les images transmises
            $images = $form->get('imageFile')->getData();
            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $ext = $image->guessExtension();
                $fichier = md5(uniqid()) . '.' . $ext;
                // On copie le fichier dans le dossier uploads
                $dossierImages = $this->getParameter('dossier_images_events');
                $image->move(
                    $dossierImages,
                    $fichier
                );
                $imageSource = $dossierImages . "/" . $fichier;
                $imageCible = $dossierImages . "/min_" . $fichier;
                // On créé une miniature du fichier image. En 3e paramètre, la largeur souhaitée en px de la miniature
                $this->creeMiniature($imageSource, $imageCible, 270);
                // On stocke le nom de l'image dans la base de données
                $img = new ImagesEvent();
                $img->setNom($fichier);
                $event->addImagesEvent($img);
            }
            $this->em->persist($event);
            $this->em->flush();
            $this->addFlash('succes', '"' . $event->getNom() . '"' . ' modifié avec succès');
            // TODO: Sécuriser la redirection en s'assurant que le referer vient bien de notre site.
            // Est-ce que ça fonctionne en https ?
            return $this->redirect($request->request->get('referer'));
        }
        return $this->render('admin/events/edit.html.twig', [
            'event' => $event,
            'form' => $form->createView()
        ]);
    }

    // ======== SUPPRIMER UN ÉVÈNEMENT ET SES IMAGES ========
    /**
     * @Route("/admin/events/edit/{id}", name="admin.event.delete", methods={"DELETE"})
     * @param Events $events
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(Events $event, Request $request)
    {
        // Vérif token pour sécuriser la suppression d'un event
        if ($this->isCsrfTokenValid('delete' . $event->getId(), $request->get('_token'))) {

            // Rechercher dans Images toutes les images de l'event en cours
            $images = $this->getDoctrine()->getRepository(ImagesEvent::class)->findBy(
                ['event' => $event->getId()]
            );
            // On récupére dans une boucle le nom de chaque image, et on la supprime sur le disque
            foreach ($images as $image) {
                $nom = $image->getNom();
                unlink($this->getParameter('dossier_images_events') . '/' . $nom);
                // On supprime également le fichier miniature
                unlink($this->getParameter('dossier_images_events') . '/min_' . $nom);
            }

            // On supprime l'event, ainsi que toutes ses images (Option orphanRemoval) dans la base
            $this->em->remove($event);
            $this->em->flush();
            $this->addFlash('succes', '"' . $event->getNom() . '"' . ' supprimé avec succès');
            //return new HttpFoundationResponse('Suppression');
        }
        return $this->redirectToRoute('admin');
    }

    // ======== SUPPRIMER UNE IMAGE ========
    /**
     * @route("/admin/events/image/supprime{id}", name="admin.events.image.delete", methods={"DELETE"})
     */
    public function deleteImage(ImagesEvent $imagesEvent, Request $request)
    {
        $data = json_decode($request->getContent(), true);
        // On vérifie si le token est valide
        if ($this->isCsrfTokenValid('delete' . $imagesEvent->getId(), $data['_token'])) {
            // On récupère le nom de l'image
            $nom = $imagesEvent->getNom();
            // On supprime le fichier
            unlink($this->getParameter('dossier_images_events') . '/' . $nom);
            // On supprime également le fichier miniature
            unlink($this->getParameter('dossier_images_events') . '/min_' . $nom);
            // On supprime le nom de l'image de la base de données
            $this->em->remove($imagesEvent);
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

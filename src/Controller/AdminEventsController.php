<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Events;
use App\Entity\ImagesEvent;
use App\Repository\EventsRepository;
use App\Service\TodayGenerator;
use App\Service\ImageTools;
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
    public function new(Request $request, TodayGenerator $todayGenerator, ImageTools $imageTools)
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
                // On créé une miniature du fichier image avec la methode createMiniature de la class ImageTools créee dans un service.
                // En 3e paramètre, la largeur souhaitée en px de la miniature
                $imageTools->createMiniature($imageSource, $imageCible, 270);


                // On stocke le nom de l'image dans la base de données
                $img = new ImagesEvent();
                $img->setNom($fichier);
                $event->addImagesEvent($img);
            }

            // On rempli le champ lastModifiedBy dans la bdd, avec le nom de l'utilisateur courant
            $event->setLastModifiedBy($this->getUser()->getEmail());

            // On rempli le champ lasModifiedAt dans la bdd, avec la date actuelle
            $event->setLastModifiedAt($todayGenerator->generateAToday());

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
    public function edit(Events $event, Request $request, TodayGenerator $todayGenerator, ImageTools $imageTools)
    {

        //Test
        $variable1 = 1;
        $variable2 = 0;
        try {
            $imageTools->test($variable1, $variable2);
        } catch (\Throwable $th) {
            $this->addFlash('error', 'On a intercepté l\'erreur');
            return $this->redirectToRoute('admin');
        }
        $this->addFlash('succes', 'Pas d\'erreur');
        return $this->redirectToRoute('admin');
        dd("stop");

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
                // On créé une miniature du fichier image avec la methode createMiniature de la class ImageTools créee dans un service.
                // En 3e paramètre, la largeur souhaitée en px de la miniature
                $imageTools->createMiniature($imageSource, $imageCible, 270);
                // On stocke le nom de l'image dans la base de données
                $img = new ImagesEvent();
                $img->setNom($fichier);
                $event->addImagesEvent($img);
            }

            // On rempli le champ lastModifiedBy dans la bdd, avec le nom de l'utilisateur courant
            $event->setLastModifiedBy($this->getUser()->getEmail());

            // On rempli le champ lasModifiedAt dans la bdd, avec la date actuelle
            $event->setLastModifiedAt($todayGenerator->generateAToday());

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

            // On récupére dans une boucle le nom de chaque image
            foreach ($images as $image) {
                $nom = $image->getNom();

                // Tableau contenant toutes les images ayant le même nom (uniqueId)
                $imagesIdentiques = $this->getDoctrine()->getRepository(ImagesEvent::class)->findBy(
                    ['nom' => $nom]
                );

                // S'il y a moins de 2 fois la même image dans la table, on peut la supprimer sur le disque, sinon, c'est qu'elle est encore utilisée par un autre event.
                if (count($imagesIdentiques) < 2) {
                    unlink($this->getParameter('dossier_images_events') . '/' . $nom);
                    // On supprime également le fichier miniature
                    unlink($this->getParameter('dossier_images_events') . '/min_' . $nom);
                }
            }

            // On supprime l'event, ainsi que tous les noms des images (Option orphanRemoval) dans la base
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
        // On récupère le nom de l'image
        $nom = $imagesEvent->getNom();
        // Tableau contenant toutes les images ayant le même nom (uniqueId)
        $imagesIdentiques = $this->getDoctrine()->getRepository(ImagesEvent::class)->findBy(
            ['nom' => $nom]
        );
        $data = json_decode($request->getContent(), true);
        // On vérifie si le token est valide
        if ($this->isCsrfTokenValid('delete' . $imagesEvent->getId(), $data['_token'])) {
            // S'il y a moins de 2 fois la même image dans la table, on peut la supprimer sur le disque, sinon, c'est qu'elle est encore utilisée par un autre event.

            if (count($imagesIdentiques) < 2) {
                // On supprime le fichier
                unlink($this->getParameter('dossier_images_events') . '/' . $nom);
                // On supprime également le fichier miniature
                unlink($this->getParameter('dossier_images_events') . '/min_' . $nom);
            }
            // On supprime le nom de l'image de la base de données
            $this->em->remove($imagesEvent);
            $this->em->flush();

            // On répond en json
            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }
}

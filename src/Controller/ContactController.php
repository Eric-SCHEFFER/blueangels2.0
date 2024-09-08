<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use App\Entity\Articles;
use App\Entity\Events;
use App\Entity\InfosEtAdresses;
use App\Service\TodayGenerator;


class ContactController extends AbstractController
{
    /**
     * @Route("/contact/{id}/{categorie}", defaults={"id" = NULL, "categorie" = NULL}, name="contact")
     */
    public function index($id, Request $request, MailerInterface $mailer, TodayGenerator $todayGenerator)
    {
        $titre = NULL;
        $endUrl = NULL;
        $categorie = $request->attributes->get("categorie");
        // Si la catégorie est vide, on vient soit d'une page event, soit du lien direct contact
        if (empty($categorie)) {
            // Si l'id de l'event existe, c'est qu'on vient d'une page event, alors, on hydrate les variables qui pré-rempliront les champs objet et message
            if (isset($id)) {
                $titre = $this->getDoctrine()->getRepository(Events::class)->find($id)->getNom();
                $endUrl = "event/" . $id;
            }
        }
        // Sinon, c'est qu'on vient d'une page article. on hydrate aussi les variables
        else {
            // Si on vient d'une page fixe du site (pas d'id dans l'url) qui affiche un article
            // if (substr($urlArticle, -1) == "/") {
            //     $pos = strrpos($urlArticle, '/', -2);
            //     $urlArticle = substr($urlArticle, 0, $pos + 1) . 'article/' . $id;
            // }
            $titre = $this->getDoctrine()->getRepository(Articles::class)->find($id)->getTitre();
            $endUrl = "article/" . $id;
        }

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        // dd($form->getErrors(true));
        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            // UnixTimeStamp de soumission du formulaire
            $sendTime = $todayGenerator->generateAToday()->getTimestamp();
            // Durée en secondes pour effectuer la soumission (sans tenir compte de l'envoi par le réseau)
            $deltaTime = $sendTime - $contact['beginTime'];
            // Durée en secondes pour effectuer la soumission, données envoyées par champ caché, côté client (en js)
            $deltaTimeClientSide = $contact['sendTimeClientSide'] - $contact['beginTimeClientSide'];

            // On vérifie plusieurs contraintes:
            // - si le champ caché email servant de "pôt de miel" aux robots spameurs est vide
            // - Si la durée de soumission du formulaire est > 3 sec (pour les robots spameurs)
            // - Si on vient d'une des pages du site

            if (
                !isset($contact['email']) &&
                $deltaTime > 3 &&
                isset($_SERVER['HTTP_ORIGIN'])
            ) {
                // C'est le véritable email. Le nom est pour tromper les robots de spam
                $expediteur = $contact['informations'];
                $objet = $contact['objet'];
                $destinataire = $this->getDoctrine()->getRepository(InfosEtAdresses::class)->findOneBy([])->getEmailEnvoiFormulaire();
                $templateTwig = "emails/contact.html.twig";
                // Envoi de l'email avec gestion d'erreur
                try {
                    // [Nouvelle politique de IONOS de 01-2024]: J'ai remplacé $expediteur par une adresse finissant en blueangelsdanse.org ou carrément site-blueangels@blueangelsdanse.org
                    // Ancienne ligne avant modifs
                    // $this->envoiEmail($mailer, $expediteur, $destinataire, $templateTwig, $objet, $contact);
                    $this->envoiEmail($mailer, "site-blueangels@blueangelsdanse.org", $destinataire, $templateTwig, $objet, $contact);
                    // S'il y a une erreur renvoyée par le serveur
                } catch (\Throwable $th) {
                    $emailContact = $this->getDoctrine()->getRepository(InfosEtAdresses::class)->findOneBy([])->getEmailContact();
                    $this->addFlash('error', 'Le serveur de messagerie n\'a pas pu envoyer votre message. Si celà arrive pour la première fois, nous vous invitons à faire une seconde tentative. Si le problème persiste, veuillez le signaler à notre adresse email de contact: ' . $emailContact . '. Pardon pour la gêne occasionnée.');
                    return $this->render('contact/contact.html.twig', [
                        'menu_courant' => 'contact',
                        'contactForm' => $form->createView(),
                        'titre' => $titre,
                        'endUrl' => $endUrl,
                    ]);
                }
                $this->addFlash('succes', 'Votre message à bien été envoyé. Nous le traiterons dans les plus brefs délais.' . ' (' . $deltaTime . ' - ' . $deltaTimeClientSide . ') ');
                // $this->addFlash('succes', $contact['beginTimeClientSide'] . ' ' . $contact['sendTimeClientSide']);
                return $this->redirectToRoute('home');
            }
        }

        return $this->render('contact/contact.html.twig', [
            'menu_courant' => 'contact',
            'contactForm' => $form->createView(),
            'titre' => $titre,
            'endUrl' => $endUrl,
        ]);
    }

    /** ======= Méhode: Envoi d'email en html, dont le corps est cherché dans une page twig ========
     *
     */
    private function envoiEmail($mailer, $expediteur, $destinataire, $templateTwig, $objet, $contact)
    {
        $email = (new TemplatedEmail())
            ->from($expediteur)
            ->to($destinataire)
            ->subject($objet)
            ->htmlTemplate($templateTwig)
            // Envoi les paramètres à la page twig
            ->context([
                'contact' => $contact
            ]);
        $mailer->send($email);
    }
}

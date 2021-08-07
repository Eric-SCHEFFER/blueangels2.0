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


class ContactController extends AbstractController
{
    /**
     * @Route("/contact/{id}/{categorie}", defaults={"id" = NULL, "categorie" = NULL}, name="contact")
     */
    public function index($id, Request $request, MailerInterface $mailer)
    {
        $titre = NULL;
        $champObjetPreRempli = NULL;
        $champMessagePreRempli = NULL;
        // Si la catégorie est vide, on vient soit d'une page event, soit du lien direct contact
        $categorie = $request->attributes->get("categorie");
        if (empty($categorie)) {
            // Si l'id de l'event existe, c'est qu'on vient d'une page event, alors, on hydrate les variables qui pré-rempliront les champs objet et message
            if (isset($id)) {
                $nom = $this->getDoctrine()->getRepository(Events::class)->find($id)->getNom();
                // TODO: Utiliser autre chose que le referer pour avoir le lien de l'article ou de l'event qui appelle la page contact
                $referer = $request->headers->get('referer');
                $champObjetPreRempli = 'A propos: ' . $nom;
                $champMessagePreRempli = "Lien de l'évènement:\n" . $referer . "\n\nBonjour,\n";
            }
        }
        // Sinon, c'est qu'on vient d'une page article. on hydrate aussi les variables
        else {
            $titre = $this->getDoctrine()->getRepository(Articles::class)->find($id)->getTitre();
            $champObjetPreRempli = 'A propos: ' . $titre;
            $urlArticleReferer = $request->headers->get('referer');
            // TODO: Corriger l'erreur du mot article qui apparait 2x dans l'url
            $pos = strrpos($urlArticleReferer, '/', -2);
            $urlArticleReferer = substr($urlArticleReferer, 0, $pos + 1) . 'article/' . $id;
            $champMessagePreRempli = "Lien de l'article (catégorie " . $categorie . "):\n" . $urlArticleReferer . "\n\nBonjour,\n";
        }
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();
            // On vérifie si le champ caché "motif" servant de "pôt de miel" aux robots spameurs est vide
            // et si on vient d'une des pages du site
            if (!isset($contact['motif']) && isset($_SERVER['HTTP_ORIGIN'])) {
                $expediteur = $contact['email'];
                $objet = $contact['objet'];
                $destinataire = $this->getDoctrine()->getRepository(InfosEtAdresses::class)->findOneBy([])->getEmailEnvoiFormulaire();
                $templateTwig = "emails/contact.html.twig";
                // Envoi du mail avec gestion d'erreur
                try {
                    $this->envoiEmail($mailer, $expediteur, $destinataire, $templateTwig, $objet, $contact);
                    // S'il y a une erreur renvoyée par le serveur
                } catch (\Throwable $th) {
                    $emailContact = $this->getDoctrine()->getRepository(InfosEtAdresses::class)->findOneBy([])->getEmailContact();
                    $this->addFlash('error', 'Votre message n\'a pas pu être envoyé. Si celà arrive pour la première fois, nous vous invitons à faire une seconde tentative. Si le problème persiste, nous vous invitons à le signaler via notre adresse email de contact: ' . $emailContact . '. Veuillez nous excuser pour la gêne occasionnée.');
                    return $this->render('contact/contact.html.twig', [
                        'menu_courant' => 'contact',
                        'contactForm' => $form->createView(),
                        'champObjetPreRempli' => $champObjetPreRempli,
                        'champMessagePreRempli' => $champMessagePreRempli,
                    ]);
                }
                $this->addFlash('succes', 'Votre message à bien été envoyé. Nous le traiterons dans les plus brefs délais. Merci.');
                return $this->redirectToRoute('home');
            }
        }
        return $this->render('contact/contact.html.twig', [
            'menu_courant' => 'contact',
            'contactForm' => $form->createView(),
            'champObjetPreRempli' => $champObjetPreRempli,
            'champMessagePreRempli' => $champMessagePreRempli,
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

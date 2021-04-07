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
        if (empty($request->attributes->get("categorie"))) {
            // Si l'id de l'event existe, c'est qu'on vient d'une page event, alors, on hydrate les variables qui pré-rempliront les champs objet et message
            if (isset($id)) {
                $titre = $this->getDoctrine()->getRepository(Events::class)->find($id)->getNom();
                $referer = $request->headers->get('referer');
                $champObjetPreRempli = 'A propos: ' . $titre;
                $champMessagePreRempli = "Lien: " . $referer . "\n\nBonjour,\n";
            }
        }
        // Sinon, c'est qu'on vient d'une page article
        else {
            $titre = $this->getDoctrine()->getRepository(Articles::class)->find($id)->getTitre();
            $referer = $request->headers->get('referer');
            $champObjetPreRempli = 'A propos: ' . $titre;
            $champMessagePreRempli = "Lien: " . $referer . "\n\nBonjour,\n";
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
                // TODO: Remettre ces lignes quand on aura crée l'entity aProposEtInfos, avec les différentes infos de contact dedans
                // $destinataire = $this->aProposEtInfosRepository->findField("email_envoi_formulaire");
                // $destinataire = $destinataire[0]['email_envoi_formulaire'];
                $destinataire = 'ericsch2e@gmail.com';

                $templateTwig = "emails/contact.html.twig";
                // Envoi du mail contenant les données du formulaire
                $this->envoiEmail($mailer, $expediteur, $destinataire, $templateTwig, $objet, $contact);
                $this->addFlash('succes', 'Le message à bien été envoyé');
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

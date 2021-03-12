<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Articles;
use App\Entity\ImagesArticle;
use App\Repository\ArticlesRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ArticleType;
use Attribute;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdminArticlesController extends AbstractController
{
    public function __construct(
        ArticlesRepository $articlesRepository,
        EntityManagerInterface $em
    ) {
        $this->articlesRepository = $articlesRepository;
        $this->em = $em;
    }

    /**
     * @Route("/admin/articles", name="admin.articles", methods={"GET"})
     */
    public function index(): Response
    {
        // On récupère tous les articles, dont en premier les épinglés s'il y en a
        $articles = $this->getArticles();
        return $this->render('admin/articles/adminArticles.html.twig', [
            'articles' => $articles,
        ]);
    }


    // ======== CRÉER UN ARTICLE ========
    /**
     * @Route("/admin/articles/nouveau", name="admin.articles.nouveau")
     */
    public function new(Request $request)
    {
        $article = new Articles();
        $form = $this->createForm(ArticleType::class, $article);
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
                $dossierImages = $this->getParameter('dossier_images_articles');
                $image->move(
                    $dossierImages,
                    $fichier
                );
                $imageSource = $dossierImages . "/" . $fichier;
                $imageCible = $dossierImages . "/min_" . $fichier;
                // On créé une miniature du fichier image. En 3e paramètre, la largeur souhaitée en px de la miniature
                $this->creeMiniature($imageSource, $imageCible, 270);
                // On stocke le nom de l'image dans la base de données
                $img = new ImagesArticle();
                $img->setNom($fichier);
                $article->addImagesArticle($img);
            }
            $this->em->persist($article);
            $this->em->flush();
            $this->addFlash('succes', 'Article créé avec succès');
            return $this->redirectToRoute('admin.articles');
        }
        return $this->render('admin/articles/nouveau.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // ======== ÉDITER UN ARTICLE ========
    /**
     * @Route("/admin/articles/edit/{id}", name="admin.articles.edit", methods="GET|POST")
     * @param Articles $articles
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Articles $article, Request $request)
    {
        $form = $this->createForm(ArticleType::class, $article);
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
                $dossierImages = $this->getParameter('dossier_images_articles');
                $image->move(
                    $dossierImages,
                    $fichier
                );
                $imageSource = $dossierImages . "/" . $fichier;
                $imageCible = $dossierImages . "/min_" . $fichier;
                // On créé une miniature du fichier image. En 3e paramètre, la largeur souhaitée en px de la miniature
                $this->creeMiniature($imageSource, $imageCible, 270);
                // On stocke le nom de l'image dans la base de données
                $img = new ImagesArticle();
                $img->setNom($fichier);
                $article->addImagesArticle($img);
            }
            $this->em->persist($article);
            $this->em->flush();
            $this->addFlash('succes', '"' . $article->getTitre() . '"' . ' modifié avec succès');
            // return $this->redirectToRoute('admin.articles');

            // TODO: Faire un retour vers la page de laquelle on vient, et sécuriser, en s'assurant que le referer vient bien de notre site.
            // Est-ce que ça fonctionne en https ?
            return $this->redirect($request->request->get('referer'));
        }
        return $this->render('admin/articles/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    // ======== SUPPRIMER UN ARTICLE ET SES IMAGES ========
    /**
     * @Route("/admin/articles/edit/{id}", name="admin.article.delete", methods={"DELETE"})
     * @param Articles $articles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(Articles $article, Request $request)
    {
        // Vérif token pour sécuriser la suppression d'une réalisation
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->get('_token'))) {

            // Rechercher dans Images toutes les images de la réalisation en cours
            $images = $this->getDoctrine()->getRepository(ImagesArticle::class)->findBy(
                ['articles' => $article->getId()]
            );
            // On récupére dans une boucle le nom de chaque image, et on la supprime sur le disque
            foreach ($images as $image) {
                $nom = $image->getNom();
                unlink($this->getParameter('dossier_images_articles') . '/' . $nom);
                // On supprime également le fichier miniature
                unlink($this->getParameter('dossier_images_articles') . '/min_' . $nom);
            }

            // On supprime la réalisation, ainsi que toutes ses images (Option orphanRemoval) dans la base
            $this->em->remove($article);
            $this->em->flush();
            $this->addFlash('succes', '"' . $article->getTitre() . '"' . ' supprimé avec succès');
            //return new HttpFoundationResponse('Suppression');
        }
        return $this->redirectToRoute('admin.articles');
    }

    // ======== SUPPRIMER UNE IMAGE ========
    /**
     * @route("/admin/articles/image/supprime{id}", name="admin.articles.image.delete", methods={"DELETE"})
     */
    public function deleteImage(ImagesArticle $imagesArticle, Request $request)
    {
        $data = json_decode($request->getContent(), true);
        // On vérifie si le token est valide
        if ($this->isCsrfTokenValid('delete' . $imagesArticle->getId(), $data['_token'])) {
            // On récupère le nom de l'image
            $nom = $imagesArticle->getNom();
            // On supprime le fichier
            unlink($this->getParameter('dossier_images_articles') . '/' . $nom);
            // On supprime également le fichier miniature
            unlink($this->getParameter('dossier_images_articles') . '/min_' . $nom);
            // On supprime le nom de l'image de la base de données
            $this->em->remove($imagesArticle);
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

    // TODO: factoriser cette fonction qui est utilisée dans plusieurs controleurs, en la mettant dans un service.
     /**
     * On récupère les articles, dont en priorité des épinglés s'il y en a.
     */
    private function getArticles()
    {
        // Articles épinglés
        $pinnedArticles = $this->articlesRepository->findAllPinnedArticles();
        // Articles non-épinglés
        $nonPinnedArticles = $this->articlesRepository->findAllArticles();
        // On fusionne les deux tableaux $pinnedArticles et $nonPinnedArticles dans $articles.
        $articles = array_merge($pinnedArticles, $nonPinnedArticles);
        return
            $articles;
    }
}

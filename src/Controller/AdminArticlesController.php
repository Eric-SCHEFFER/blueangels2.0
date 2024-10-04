<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Articles;
use App\Entity\ImagesArticle;
use App\Repository\ArticlesRepository;
use App\Entity\CategoriesArticle;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ArticleType;
use Attribute;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\TodayGenerator;
use App\Service\ImageTools;

class AdminArticlesController extends AbstractController
{
    private $articlesRepository;
    private $em;

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
        // Récuper l'id de la catégorie 'Non classé', pour après l'avoir dans le select d'une création d'article (car on est dans l'admin des articles en général)
        $repoCat = $this->getDoctrine()->getRepository(CategoriesArticle::class);
        $categorie = $repoCat->findOneBy(array('nom' => 'Non classé'));
        $categorieId = $categorie->getId();
        return $this->render('admin/articles/adminArticles.html.twig', [
            'articles' => $articles,
            'categorieId' => $categorieId,
        ]);
    }


    // ======== CRÉER UN ARTICLE ========
    /**
     * @Route("/admin/articles/nouveau/{categorieId}", name="admin.articles.nouveau", methods="GET|POST")
     */
    public function new(Request $request, TodayGenerator $todayGenerator, ImageTools $imageTools)
    {
        $article = new Articles();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->editAndNew($form, $imageTools, $todayGenerator, $article);
            return $this->redirectToRoute('admin');
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
    public function edit(Articles $article, Request $request, TodayGenerator $todayGenerator, ImageTools $imageTools)
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->editAndNew($form, $imageTools, $todayGenerator, $article);
            return $this->redirectToRoute('admin');
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
        return $this->redirectToRoute('admin');
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

    // TODO: Factoriser si possible cette fonction qui est utilisée dans plusieurs controleurs, en la mettant dans un service.
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

    /**
     * Code commun pour éditer ou créer un nouvel event
     */
    private function editAndNew($form, $imageTools, $todayGenerator, $article)
    {
        // On récupère les images transmises
        $images = $form->get('imageFile')->getData();
        // On boucle sur les images
        foreach ($images as $image) {
            try {
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
                // On créé une miniature du fichier image avec la methode createMiniature de la class ImageTools créee dans un service.
                // En 3e paramètre, la largeur souhaitée en px de la miniature
                $imageTools->createMiniature($imageSource, $imageCible, 270);

                // On stocke le nom de l'image dans la base de données
                $img = new ImagesArticle();
                $img->setNom($fichier);
                $article->addImagesArticle($img);
            } catch (\Throwable $th) {
                if (file_exists($imageSource)) {
                    unlink($imageSource);
                }
                if (file_exists($imageCible)) {
                    unlink($imageCible);
                }
                // On récupére le nom d'origine de l'image en erreur
                $imagesErr[] = $image->getClientOriginalName();
            }
        }
        // On rempli le champ lastModifiedBy dans la bdd, avec le nom de l'utilisateur courant
        $article->setLastModifiedBy($this->getUser()->getEmail());
        // On rempli le champ lasModifiedAt dans la bdd, avec la date actuelle
        $article->setLastModifiedAt($todayGenerator->generateAToday());

        $this->em->persist($article);
        $this->em->flush();
        $this->addFlash('succes', '"' . $article->getTitre() . '"' . ' Créé ou mis à jour');
        // S'il y a au moins une erreur à la création de la ou des miniatures, on on affiche chaque nom de l'image non créee
        if (isset($imagesErr)) {
            $this->addFlash('error', 'Les images suivantes n\'ont pas pu être ajoutées:');
            foreach ($imagesErr as $imageErr) {
                $this->addFlash('error', $imageErr . ' ' . '[' . substr($th, 0, 95) . '...]');
            }
        }
    }
}

// VIEUX / ON
// public function edit(Articles $article, Request $request, TodayGenerator $todayGenerator, ImageTools $imageTools)
//     {
//         $form = $this->createForm(ArticleType::class, $article);
//         $form->handleRequest($request);
//         if ($form->isSubmitted() && $form->isValid()) {
//             // On récupère les images transmises
//             $images = $form->get('imageFile')->getData();
//             // On boucle sur les images
//             foreach ($images as $image) {
//                 try {
//                     // On génère un nouveau nom de fichier
//                     $ext = $image->guessExtension();
//                     $fichier = md5(uniqid()) . '.' . $ext;
//                     // On copie le fichier dans le dossier uploads
//                     $dossierImages = $this->getParameter('dossier_images_articles');
//                     $image->move(
//                         $dossierImages,
//                         $fichier
//                     );
//                     $imageSource = $dossierImages . "/" . $fichier;
//                     $imageCible = $dossierImages . "/min_" . $fichier;
//                     // On créé une miniature du fichier image avec la methode createMiniature de la class ImageTools créee dans un service.
//                     // En 3e paramètre, la largeur souhaitée en px de la miniature
//                     $imageTools->createMiniature($imageSource, $imageCible, 270);

//                     // On stocke le nom de l'image dans la base de données
//                     $img = new ImagesArticle();
//                     $img->setNom($fichier);
//                     $article->addImagesArticle($img);
//                 } catch (\Throwable $th) {
//                     if (file_exists($imageSource)) {
//                         unlink($imageSource);
//                     }
//                     if (file_exists($imageCible)) {
//                         unlink($imageCible);
//                     }
//                     // On récupére le nom d'origine de l'image en erreur
//                     $imagesErr[] = $image->getClientOriginalName();
//                 }
//             }
//             // On rempli le champ lastModifiedBy dans la bdd, avec le nom de l'utilisateur courant
//             $article->setLastModifiedBy($this->getUser()->getEmail());
//             // On rempli le champ lasModifiedAt dans la bdd, avec la date actuelle
//             $article->setLastModifiedAt($todayGenerator->generateAToday());

//             $this->em->persist($article);
//             $this->em->flush();
//             $this->addFlash('succes', '"' . $article->getTitre() . '"' . ' Mis à jour');
//             // S'il y a au moins une erreur à la création de la ou des miniatures, on on affiche chaque nom de l'image non créee
//             if (isset($imagesErr)) {
//                 $this->addFlash('error', 'Les images suivantes n\'ont pas pu être ajoutées:');
//                 foreach ($imagesErr as $imageErr) {
//                     $this->addFlash('error', $imageErr . ' ' . '[' . substr($th, 0, 95) . '...]');
//                 }
//             }
//             return $this->redirectToRoute('admin');
//         }

//         return $this->render('admin/articles/edit.html.twig', [
//             'article' => $article,
//             'form' => $form->createView()
//         ]);
//     }
// VIEUX / OFF
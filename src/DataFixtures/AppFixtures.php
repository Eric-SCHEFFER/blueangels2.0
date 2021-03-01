<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Repository\CategoriesImageRepository;
use App\Repository\EventsRepository;
use App\Repository\ArticlesRepository;
use DateTime;

use App\Entity\CategoriesImage;
use App\Entity\ImagesEvent;
use App\Entity\ImagesArticle;
use App\Entity\Events;
use App\Entity\Articles;
use App\Entity\Communique;

class AppFixtures extends Fixture
{
    public function __construct(
        CategoriesImageRepository $categoriesImageRepository,
        EventsRepository $eventsRepository,
        ArticlesRepository $articlesRepository
    ) {
        $this->categoriesImageRepository = $categoriesImageRepository;
        $this->eventsRepository = $eventsRepository;
        $this->articlesRepository = $articlesRepository;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $lorem = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';


        // Events
        for ($i = 1; $i <= 20; $i++) {
            $event = new Events();
            $event->setNom('Évènement ' . $i);
            $event->setHook('Hook de l\'évènement ' . $i);
            $event->setDateEvent(new DateTime('2021-01-' . $i));
            $event->setDescription('Description de l\'évènement ' . $i . ' ' . str_repeat($lorem, 100));
            $manager->persist($event);
        }
        $manager->flush();

        // CategoriesImage
        for ($i = 1; $i <= 5; $i++) {
            $categoriesImage = new CategoriesImage();
            $categoriesImage->setNom('categorie ' . $i);
            $manager->persist($categoriesImage);
        }
        $manager->flush();
        $categorie1Id = $this->categoriesImageRepository->findOneBy(['nom' => 'categorie 1']);
        $categorie2Id = $this->categoriesImageRepository->findOneBy(['nom' => 'categorie 2']);

        // ImagesEvent
        for ($i = 1; $i <= 10; $i++) {
            $imagesEvent = new ImagesEvent();
            $imagesEvent->setNom('image-evenement' . $i . '.jpg');
            $imagesEvent->setCategoriesImage($categorie1Id);
            $imagesEvent->setEvent($this->eventsRepository->findOneBy(['nom' => 'Évènement ' . $i]));
            $manager->persist($imagesEvent);
        }
        for ($i = 10; $i <= 20; $i++) {
            $imagesEvent = new ImagesEvent();
            $imagesEvent->setNom('image-evenement' . $i . '.jpg');
            $imagesEvent->setCategoriesImage($categorie2Id);
            $imagesEvent->setEvent($this->eventsRepository->findOneBy(['nom' => 'Évènement ' . $i]));
            $manager->persist($imagesEvent);
        }

        // Articles
        for ($i = 1; $i <= 20; $i++) {
            $article = new Articles();
            $article->setTitre('Article ' . $i);
            $article->setHook('Hook de l\'article ' . $i);
            $article->setCreatedAt(new DateTime('2021-01-' . $i));
            $article->setContenu('Description de l\'article ' . $i . ' ' . str_repeat($lorem, 100));
            $manager->persist($article);
        }
        $manager->flush();

        // ImagesArticle
        for ($i = 1; $i <= 20; $i++) {
            $imagesArticle = new ImagesArticle();
            $imagesArticle->setNom('image-article' . $i . '.jpg');
            $imagesArticle->setArticles($this->articlesRepository->findOneBy(['titre' => 'Article ' . $i]));
            $manager->persist($imagesArticle);
        }

        // Communique
        for ($i = 1; $i <= 3; $i++) {
            $communique = new Communique();
            $communique->setTitre('Communiqué ' . $i);
            $communique->setHook('Hook du communiqué ' . $i);
            $communique->setCreatedAt(new DateTime('2021-01-' . $i));
            $communique->setContenu('Contenu du communiqué ' . $i . ' ' . str_repeat($lorem, 100));
            $manager->persist($communique);
        }
        $manager->flush();
    }
}

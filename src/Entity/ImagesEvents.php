<?php

namespace App\Entity;

use App\Repository\ImagesEventsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImagesEventsRepository::class)
 */
class ImagesEvents
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity=Events::class, inversedBy="imagesEvents")
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity=ImagesCategories::class, inversedBy="imagesEvents")
     */
    private $images_categorie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getEvent(): ?Events
    {
        return $this->event;
    }

    public function setEvent(?Events $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getImagesCategorie(): ?ImagesCategories
    {
        return $this->images_categorie;
    }

    public function setImagesCategorie(?ImagesCategories $images_categorie): self
    {
        $this->images_categorie = $images_categorie;

        return $this;
    }
}

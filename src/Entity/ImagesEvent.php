<?php

namespace App\Entity;

use App\Repository\ImagesEventRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImagesEventRepository::class)
 */
class ImagesEvent
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
     * @ORM\ManyToOne(targetEntity=CategoriesImage::class, inversedBy="imagesEvents")
     */
    private $categories_image;

    /**
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private $caption;

    /**
     * @ORM\Column(type="string", length=455, nullable=true)
     */
    private $author;

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

    public function getCategoriesImage(): ?CategoriesImage
    {
        return $this->categories_image;
    }

    public function setCategoriesImage(?CategoriesImage $categories_image): self
    {
        $this->categories_image = $categories_image;

        return $this;
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function setCaption(?string $caption): self
    {
        $this->caption = $caption;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): self
    {
        $this->author = $author;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\CategoriesImageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoriesImageRepository::class)
 */
class CategoriesImage
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
     * @ORM\OneToMany(targetEntity=ImagesEvent::class, mappedBy="categories_image")
     */
    private $imagesEvents;

    public function __construct()
    {
        $this->imagesEvents = new ArrayCollection();
    }

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

    /**
     * @return Collection|ImagesEvent[]
     */
    public function getImagesEvents(): Collection
    {
        return $this->imagesEvents;
    }

    public function addImagesEvent(ImagesEvent $imagesEvent): self
    {
        if (!$this->imagesEvents->contains($imagesEvent)) {
            $this->imagesEvents[] = $imagesEvent;
            $imagesEvent->setCategoriesImage($this);
        }

        return $this;
    }

    public function removeImagesEvent(ImagesEvent $imagesEvent): self
    {
        if ($this->imagesEvents->removeElement($imagesEvent)) {
            // set the owning side to null (unless already changed)
            if ($imagesEvent->getCategoriesImage() === $this) {
                $imagesEvent->setCategoriesImage(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\EventsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventsRepository::class)
 */
class Events
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $hook;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_event;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=ImagesEvent::class, mappedBy="event")
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

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getHook(): ?string
    {
        return $this->hook;
    }

    public function setHook(?string $hook): self
    {
        $this->hook = $hook;

        return $this;
    }

    public function getDateEvent(): ?\DateTimeInterface
    {
        return $this->date_event;
    }

    public function setDateEvent(\DateTimeInterface $date_event): self
    {
        $this->date_event = $date_event;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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
            $imagesEvent->setEvent($this);
        }

        return $this;
    }

    public function removeImagesEvent(ImagesEvent $imagesEvent): self
    {
        if ($this->imagesEvents->removeElement($imagesEvent)) {
            // set the owning side to null (unless already changed)
            if ($imagesEvent->getEvent() === $this) {
                $imagesEvent->setEvent(null);
            }
        }

        return $this;
    }
}

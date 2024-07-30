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
     * @ORM\OneToMany(targetEntity=ImagesEvent::class, mappedBy="event", orphanRemoval=true, cascade={"persist"})
     */
    private $imagesEvents;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $annule;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $actif;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $event_blue_angels;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $epingle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom_lieu;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code_postal;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=800, nullable=true)
     */
    private $lien_maps;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $last_modified_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $last_modified_by;

    public function __construct()
    {
        $this->imagesEvents = new ArrayCollection();
        $this->date_event = new \DateTime('now');
        $this->actif = true;
        $this->event_blue_angels = true;
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

    public function getAnnule(): ?bool
    {
        return $this->annule;
    }

    public function setAnnule(?bool $annule): self
    {
        $this->annule = $annule;

        return $this;
    }

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(?bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getEventBlueAngels(): ?bool
    {
        return $this->event_blue_angels;
    }

    public function setEventBlueAngels(?bool $event_blue_angels): self
    {
        $this->event_blue_angels = $event_blue_angels;

        return $this;
    }

    public function getEpingle(): ?bool
    {
        return $this->epingle;
    }

    public function setEpingle(?bool $epingle): self
    {
        $this->epingle = $epingle;

        return $this;
    }

    public function getNomLieu(): ?string
    {
        return $this->nom_lieu;
    }

    public function setNomLieu(?string $nom_lieu): self
    {
        $this->nom_lieu = $nom_lieu;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->code_postal;
    }

    public function setCodePostal(?string $code_postal): self
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getLienMaps(): ?string
    {
        return $this->lien_maps;
    }

    public function setLienMaps(?string $lien_maps): self
    {
        $this->lien_maps = $lien_maps;

        return $this;
    }

    public function getLastModifiedAt(): ?\DateTimeInterface
    {
        return $this->last_modified_at;
    }

    public function setLastModifiedAt(?\DateTimeInterface $last_modified_at): self
    {
        $this->last_modified_at = $last_modified_at;

        return $this;
    }

    public function getLastModifiedBy(): ?string
    {
        return $this->last_modified_by;
    }

    public function setLastModifiedBy(?string $last_modified_by): self
    {
        $this->last_modified_by = $last_modified_by;

        return $this;
    }
}

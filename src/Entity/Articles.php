<?php

namespace App\Entity;

use App\Repository\ArticlesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticlesRepository::class)
 */
class Articles
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
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hook;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="text")
     */
    private $contenu;

    /**
     * @ORM\OneToMany(targetEntity=ImagesArticle::class, mappedBy="articles", orphanRemoval=true, cascade={"persist"})
     */
    private $imagesArticles;

    public function __construct()
    {
        $this->imagesArticles = new ArrayCollection();
        $this->created_at = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getHook(): ?string
    {
        return $this->hook;
    }

    public function setHook(string $hook): self
    {
        $this->hook = $hook;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * @return Collection|ImagesArticle[]
     */
    public function getImagesArticles(): Collection
    {
        return $this->imagesArticles;
    }

    public function addImagesArticle(ImagesArticle $imagesArticle): self
    {
        if (!$this->imagesArticles->contains($imagesArticle)) {
            $this->imagesArticles[] = $imagesArticle;
            $imagesArticle->setArticles($this);
        }

        return $this;
    }

    public function removeImagesArticle(ImagesArticle $imagesArticle): self
    {
        if ($this->imagesArticles->removeElement($imagesArticle)) {
            // set the owning side to null (unless already changed)
            if ($imagesArticle->getArticles() === $this) {
                $imagesArticle->setArticles(null);
            }
        }

        return $this;
    }
}

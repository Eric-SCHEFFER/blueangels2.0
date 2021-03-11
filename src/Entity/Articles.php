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

    /**
     * @ORM\ManyToOne(targetEntity=CategoriesArticle::class, inversedBy="articles")
     */
    private $categories_article;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $epingle;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $actif;

    public function __construct()
    {
        $this->imagesArticles = new ArrayCollection();
        $this->created_at = new \DateTime('now');
        $this->actif = true;
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

    public function getCategoriesArticle(): ?CategoriesArticle
    {
        return $this->categories_article;
    }

    public function setCategoriesArticle(?CategoriesArticle $categories_article): self
    {
        $this->categories_article = $categories_article;

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

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(?bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }
}

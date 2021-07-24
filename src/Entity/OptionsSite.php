<?php

namespace App\Entity;

use App\Repository\OptionsSiteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OptionsSiteRepository::class)
 */
class OptionsSite
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $barre_communique_homepage;

    public function __construct()
    {
        $this->barre_communique_homepage = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBarreCommuniqueHomepage(): ?bool
    {
        return $this->barre_communique_homepage;
    }

    public function setBarreCommuniqueHomepage(?bool $barre_communique_homepage): self
    {
        $this->barre_communique_homepage = $barre_communique_homepage;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\InfosEtAdressesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InfosEtAdressesRepository::class)
 */
class InfosEtAdresses
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
    private $nom_entreprise;

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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email_contact;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email_envoi_formulaire;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $facebook;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $youtube;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telephone_2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $complement_adresse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $autre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lieu_cours;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse_cours;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code_postal_cours;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ville_cours;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $google_maps_cours;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEntreprise(): ?string
    {
        return $this->nom_entreprise;
    }

    public function setNomEntreprise(string $nom_entreprise): self
    {
        $this->nom_entreprise = $nom_entreprise;

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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getEmailContact(): ?string
    {
        return $this->email_contact;
    }

    public function setEmailContact(string $email_contact): self
    {
        $this->email_contact = $email_contact;

        return $this;
    }

    public function getEmailEnvoiFormulaire(): ?string
    {
        return $this->email_envoi_formulaire;
    }

    public function setEmailEnvoiFormulaire(string $email_envoi_formulaire): self
    {
        $this->email_envoi_formulaire = $email_envoi_formulaire;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getYoutube(): ?string
    {
        return $this->youtube;
    }

    public function setYoutube(?string $youtube): self
    {
        $this->youtube = $youtube;

        return $this;
    }

    public function getTelephone2(): ?string
    {
        return $this->telephone_2;
    }

    public function setTelephone2(?string $telephone_2): self
    {
        $this->telephone_2 = $telephone_2;

        return $this;
    }

    public function getComplementAdresse(): ?string
    {
        return $this->complement_adresse;
    }

    public function setComplementAdresse(?string $complement_adresse): self
    {
        $this->complement_adresse = $complement_adresse;

        return $this;
    }

    public function getAutre(): ?string
    {
        return $this->autre;
    }

    public function setAutre(?string $autre): self
    {
        $this->autre = $autre;

        return $this;
    }

    public function getLieuCours(): ?string
    {
        return $this->lieu_cours;
    }

    public function setLieuCours(?string $lieu_cours): self
    {
        $this->lieu_cours = $lieu_cours;

        return $this;
    }

    public function getAdresseCours(): ?string
    {
        return $this->adresse_cours;
    }

    public function setAdresseCours(?string $adresse_cours): self
    {
        $this->adresse_cours = $adresse_cours;

        return $this;
    }

    public function getCodePostalCours(): ?string
    {
        return $this->code_postal_cours;
    }

    public function setCodePostalCours(?string $code_postal_cours): self
    {
        $this->code_postal_cours = $code_postal_cours;

        return $this;
    }

    public function getVilleCours(): ?string
    {
        return $this->ville_cours;
    }

    public function setVilleCours(?string $ville_cours): self
    {
        $this->ville_cours = $ville_cours;

        return $this;
    }

    public function getGoogleMapsCours(): ?string
    {
        return $this->google_maps_cours;
    }

    public function setGoogleMapsCours(?string $google_maps_cours): self
    {
        $this->google_maps_cours = $google_maps_cours;

        return $this;
    }
}

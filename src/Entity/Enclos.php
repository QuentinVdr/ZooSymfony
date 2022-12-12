<?php

namespace App\Entity;

use App\Repository\EnclosRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EnclosRepository::class)]
class Enclos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?int $superficie = null;

    #[ORM\Column]
    private ?int $maxAnimaux = null;

    #[ORM\Column]
    private ?bool $quarantaine = null;

    #[ORM\ManyToOne(inversedBy: 'enclos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?espace $espace = null;

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

    public function getSuperficie(): ?int
    {
        return $this->superficie;
    }

    public function setSuperficie(int $superficie): self
    {
        $this->superficie = $superficie;

        return $this;
    }

    public function getMaxAnimaux(): ?int
    {
        return $this->maxAnimaux;
    }

    public function setMaxAnimaux(int $maxAnimaux): self
    {
        $this->maxAnimaux = $maxAnimaux;

        return $this;
    }

    public function isQuarantaine(): ?bool
    {
        return $this->quarantaine;
    }

    public function setQuarantaine(bool $quarantaine): self
    {
        $this->quarantaine = $quarantaine;

        return $this;
    }

    public function getEspace(): ?espace
    {
        return $this->espace;
    }

    public function setEspace(?espace $espace): self
    {
        $this->espace = $espace;

        return $this;
    }
}

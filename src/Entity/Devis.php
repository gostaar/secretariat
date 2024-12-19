<?php

namespace App\Entity;

use App\Repository\DevisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\DevisStatus;


#[ORM\Entity(repositoryClass: DevisRepository::class)]
class Devis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $montant = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date_devis = null;

    #[ORM\Column(type: 'string', enumType: DevisStatus::class)]
    private DevisStatus $status = DevisStatus::EN_ATTENTE;

    #[ORM\ManyToOne(inversedBy: 'devis')]
    private ?User $client = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\Column]
    private ?bool $is_active = false;

    public function __construct()
    {
        // $this->status = DevisStatus::EN_ATTENTE->value;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'montant' => $this->getMontant(),
            'date_devis' => $this->getDateDevis(),
            'client' => $this->getClient(),
            'commentaire' => $this->getCommentaire(),
            'is_active' => $this->isActive(),
            'status' => $this->getStatusLabel(),
        ];
    }

    public function __toString(): string{
        return 'Devis n° '.$this->id.' '.number_format($this->montant, 2, ',', ' ') . ' €';;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDateDevis(): ?\DateTimeInterface
    {
        return $this->date_devis;
    }

    public function setDateDevis(\DateTimeInterface $date_devis): static
    {
        $this->date_devis = $date_devis;

        return $this;
    }

    public function getStatus(): DevisStatus
    {
        return $this->status;
    }

    public function getStatusLabel(): string
    {
        return $this->status->value;
    }

    public function setStatus(DevisStatus $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): static
    {
        $this->is_active = $is_active;

        return $this;
    }
}

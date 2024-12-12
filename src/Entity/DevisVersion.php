<?php

namespace App\Entity;

use App\Repository\DevisVersionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\DevisStatus;

#[ORM\Entity(repositoryClass: DevisVersionRepository::class)]
class DevisVersion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $devisVersionId = null;

    #[ORM\Column]
    private ?float $devisVersionMontant = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $devisVersionDateDevis = null;

    #[ORM\Column(length: 255)]
    private ?string $devisVersionCommentaire = null;

    #[ORM\Column]
    private ?bool $devisVersionIsActive = null;

    #[ORM\Column(type: 'string', enumType: DevisStatus::class)]
    private ?string $devisVersionStatus = null;

    public function __construct()
    {
        // Définir une valeur par défaut pour le statut, par exemple "pending"
        $this->devisVersionStatus = DevisStatus::EN_ATTENTE->value;
    }

    public function getDevisVersionId(): ?int
    {
        return $this->devisVersionId;
    }

    public function setDevisVersionId(int $devisVersionId): static
    {
        $this->devisVersionId = $devisVersionId;

        return $this;
    }

    public function getDevisVersionMontant(): ?float
    {
        return $this->devisVersionMontant;
    }

    public function setDevisVersionMontant(float $devisVersionMontant): static
    {
        $this->devisVersionMontant = $devisVersionMontant;

        return $this;
    }

    public function getDevisVersionDateDevis(): ?\DateTimeInterface
    {
        return $this->devisVersionDateDevis;
    }

    public function setDevisVersionDateDevis(\DateTimeInterface $devisVersionDateDevis): static
    {
        $this->devisVersionDateDevis = $devisVersionDateDevis;

        return $this;
    }

    public function getDevisVersionStatus(): ?string
    {
        return $this->devisVersionStatus;
    }

    public function setDevisVersionStatus(string $devisVersionStatus): static
    {
        if (!in_array($devisVersionStatus, DevisStatus::getValues())) {
            throw new \InvalidArgumentException("Invalid status value");
        }
        $this->devisVersionStatus = $devisVersionStatus;
        return $this;
    }

    public function getDevisVersionCommentaire(): ?string
    {
        return $this->devisVersionCommentaire;
    }

    public function setDevisVersionCommentaire(string $devisVersionCommentaire): static
    {
        $this->devisVersionCommentaire = $devisVersionCommentaire;

        return $this;
    }

    public function isDevisVersionActive(): ?bool
    {
        return $this->devisVersionIsActive;
    }

    public function setDevisVersionActive(bool $devisVersionIsActive): static
    {
        $this->devisVersionIsActive = $devisVersionIsActive;

        return $this;
    }
}

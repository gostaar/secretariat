<?php

namespace App\Entity;

use App\Repository\PaiementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaiementRepository::class)]
class Paiement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $paiementId = null;

    #[ORM\ManyToOne(inversedBy: 'paiements')]
    private ?Facture $paiementFacture = null;

    #[ORM\Column]
    private ?float $paiementMontantPaye = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $paiementDatePaiement = null;

    public function getPaiementId(): ?int
    {
        return $this->paiementId;
    }

    public function getPaiementFacture(): ?Facture
    {
        return $this->paiementFacture;
    }

    public function setPaiementFacture(?Facture $paiementFacture): static
    {
        $this->paiementFacture = $paiementFacture;

        return $this;
    }

    public function getPaiementMontantPaye(): ?float
    {
        return $this->paiementMontantPaye;
    }

    public function setPaiementMontantPaye(float $paiementMontantPaye): static
    {
        $this->paiementMontantPaye = $paiementMontantPaye;

        return $this;
    }

    public function getPaiementDatePaiement(): ?\DateTimeInterface
    {
        return $this->paiementDatePaiement;
    }

    public function setPaiementDatePaiement(\DateTimeInterface $paiementDatePaiement): static
    {
        $this->paiementDatePaiement = $paiementDatePaiement;

        return $this;
    }
}

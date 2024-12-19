<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\FactureStatus;


#[ORM\Entity(repositoryClass: FactureRepository::class)]
class Facture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $montant = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_paiement = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date_facture = null;

    #[ORM\Column(type: 'string', enumType: FactureStatus::class)]
    private FactureStatus $status = FactureStatus::EN_ATTENTE;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    private ?User $client = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\Column]
    private ?bool $is_active = false;

    /**
     * @var Collection<int, Paiement>
     */
    #[ORM\OneToMany(targetEntity: Paiement::class, mappedBy: 'facture')]
    private Collection $paiements;

    public function __construct()
    {
        // Définir une valeur par défaut pour le statut, par exemple "pending"
        // $this->status = FactureStatus::NON_PAYE->value;
        $this->paiements = new ArrayCollection();
    }

    public function __toString(): string{
        return 'Facture n° '.$this->id.' '.number_format($this->montant, 2, ',', ' ') . ' €';;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'montant' => $this->getMontant(),
            'date_paiement' => $this->getDatePaiement(),
            'date_facture' => $this->getDateFacture(),
            'client' => $this->getClient(),
            'commentaire' => $this->getCommentaire(),
            'is_active' => $this->isActive(),
            'paiements' => $this->getPaiements(),
            'status' => $this->getStatusLabel(),
        ];
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

    public function getDatePaiement(): ?\DateTimeInterface
    {
        return $this->date_paiement;
    }

    public function setDatePaiement(\DateTimeInterface $date_paiement): static
    {
        $this->date_paiement = $date_paiement;

        return $this;
    }

    public function getDateFacture(): ?\DateTimeImmutable
    {
        return $this->date_facture;
    }

    public function setDateFacture(\DateTimeImmutable $date_facture): static
    {
        $this->date_facture = $date_facture;

        return $this;
    }

    public function getStatus(): FactureStatus
    {
        return $this->status;
    }

    public function getStatusLabel(): string
    {
        return $this->status->value;
    }

    public function setStatus(FactureStatus $status): self
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

    public function setActive(bool $is_active): static
    {
        $this->is_active = $is_active;

        return $this;
    }

    /**
     * @return Collection<int, Paiement>
     */
    public function getPaiements(): Collection
    {
        return $this->paiements;
    }

    public function addPaiement(Paiement $paiement): static
    {
        if (!$this->paiements->contains($paiement)) {
            $this->paiements->add($paiement);
            $paiement->setFacture($this);
        }

        return $this;
    }

    public function removePaiement(Paiement $paiement): static
    {
        if ($this->paiements->removeElement($paiement)) {
            // set the owning side to null (unless already changed)
            if ($paiement->getFacture() === $this) {
                $paiement->setFacture(null);
            }
        }

        return $this;
    }
}

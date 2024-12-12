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
    private ?int $devisId = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?float $devisMontant = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $devisDatePaiement = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $devisDateFacture = null;

    #[ORM\Column(type: 'string', enumType: FactureStatus::class)]
    private string $devisStatus;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    private ?User $devisClient = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $devisCommentaire = null;

    #[ORM\Column]
    private ?bool $devisIsActive = null;

    /**
     * @var Collection<int, Paiement>
     */
    #[ORM\OneToMany(targetEntity: Paiement::class, mappedBy: 'facture')]
    private Collection $devisPaiements;

    public function __construct()
    {
        $this->devisStatus = FactureStatus::NON_PAYE->value;
        $this->devisPaiements = new ArrayCollection();
    }

    public function __toString(): string{
        return $this->devisId.' '.$this->devisStatus;
    }

    public function getDevisId(): ?int
    {
        return $this->devisId;
    }

    public function setDevisId(int $devisId): static
    {
        $this->devisId = $devisId;

        return $this;
    }

    public function getDevisMontant(): ?float
    {
        return $this->devisMontant;
    }

    public function setDevisMontant(float $devisMontant): static
    {
        $this->devisMontant = $devisMontant;

        return $this;
    }

    public function getDevisDatePaiement(): ?\DateTimeInterface
    {
        return $this->devisDatePaiement;
    }

    public function setDevisDatePaiement(\DateTimeInterface $devisDatePaiement): static
    {
        $this->devisDatePaiement = $devisDatePaiement;

        return $this;
    }

    public function getDevisDateFacture(): ?\DateTimeImmutable
    {
        return $this->devisDateFacture;
    }

    public function setDevisDateFacture(\DateTimeImmutable $devisDateFacture): static
    {
        $this->devisDateFacture = $devisDateFacture;

        return $this;
    }

    public function getDevisStatus(): ?string
    {
        return $this->devisStatus;
    }

    public function setDevisStatus(string $devisStatus): static
    {
        if (!in_array($devisStatus, FactureStatus::getValues())) {
            throw new \InvalidArgumentException("Invalid status value");
        }
        $this->devisStatus = $devisStatus;
        return $this;
    }

    public function getDevisClient(): ?User
    {
        return $this->devisClient;
    }

    public function setDevisClient(?User $devisClient): static
    {
        $this->devisClient = $devisClient;

        return $this;
    }

    public function getDevisCommentaire(): ?string
    {
        return $this->devisCommentaire;
    }

    public function setDevisCommentaire(?string $devisCommentaire): static
    {
        $this->devisCommentaire = $devisCommentaire;

        return $this;
    }

    public function getDevisIsActive(): ?bool
    {
        return $this->devisIsActive;
    }

    public function setDevisIsActive(bool $devisIsActive): static
    {
        $this->devisIsActive = $devisIsActive;

        return $this;
    }

    /**
     * @return Collection<int, Paiement>
     */
    public function getDevisPaiements(): Collection
    {
        return $this->devisPaiements;
    }

    public function addDevisPaiement(Paiement $paiement): static
    {
        if (!$this->devisPaiements->contains($paiement)) {
            $this->devisPaiements->add($paiement);
            $paiement->setFacture($this);
        }

        return $this;
    }

    public function removeDevisPaiement(Paiement $paiement): static
    {
        if ($this->devisPaiements->removeElement($paiement)) {
            // set the owning side to null (unless already changed)
            if ($paiement->getFacture() === $this) {
                $paiement->setFacture(null);
            }
        }

        return $this;
    }
}

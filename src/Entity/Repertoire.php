<?php

namespace App\Entity;

use App\Repository\RepertoireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RepertoireRepository::class)]
class Repertoire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $repertoireId = null;

    #[ORM\Column(length: 255)]
    private ?string $repertoireNom = null;

    #[ORM\Column(length: 255)]
    private ?string $repertoireAdresse = null;

    #[ORM\Column(length: 10)]
    private ?string $repertoireCodePostal = null;

    #[ORM\Column(length: 100)]
    private ?string $repertoireVille = null;

    #[ORM\Column(length: 100)]
    private ?string $repertoirePays = null;

    #[ORM\Column(length: 20)]
    private ?string $repertoireTelephone = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $repertoireMobile = null;

    #[ORM\Column(length: 180)]
    private ?string $repertoireEmail = null;

    #[ORM\Column(length: 20)]
    private ?string $repertoireSiret = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $repertoireNomEntreprise = null;

    #[ORM\ManyToOne(inversedBy: 'repertoires')]
    private ?User $repertoireClient = null;

    #[ORM\ManyToOne(inversedBy: 'repertoires')]
    private ?Dossier $repertoireDossier = null;

    /**
     * @var Collection<int, Contact>
     */
    #[ORM\OneToMany(targetEntity: Contact::class, mappedBy: 'repertoireContact')]
    private Collection $repertoireContact;

    public function __construct()
    {
        $this->repertoireContact = new ArrayCollection();
    }

    public function getRepertoireId(): ?int
    {
        return $this->repertoireId;
    }

    public function getRepertoireNom(): ?string
    {
        return $this->repertoireNom;
    }

    public function setRepertoireNom(string $repertoireNom): static
    {
        $this->repertoireNom = $repertoireNom;

        return $this;
    }

    public function getRepertoireAdresse(): ?string
    {
        return $this->repertoireAdresse;
    }

    public function setRepertoireAdresse(string $repertoireAdresse): static
    {
        $this->repertoireAdresse = $repertoireAdresse;

        return $this;
    }

    public function getRepertoireCodePostal(): ?string
    {
        return $this->repertoireCodePostal;
    }

    public function setRepertoireCodePostal(string $repertoireCodePostal): static
    {
        $this->repertoireCodePostal = $repertoireCodePostal;

        return $this;
    }

    public function getRepertoireVille(): ?string
    {
        return $this->repertoireVille;
    }

    public function setRepertoireVille(string $repertoireVille): static
    {
        $this->repertoireVille = $repertoireVille;

        return $this;
    }

    public function getRepertoirePays(): ?string
    {
        return $this->repertoirePays;
    }

    public function setRepertoirePays(string $repertoirePays): static
    {
        $this->repertoirePays = $repertoirePays;

        return $this;
    }

    public function getRepertoireTelephone(): ?string
    {
        return $this->repertoireTelephone;
    }

    public function setRepertoireTelephone(string $repertoireTelephone): static
    {
        $this->repertoireTelephone = $repertoireTelephone;

        return $this;
    }

    public function getRepertoireMobile(): ?string
    {
        return $this->repertoireMobile;
    }

    public function setRepertoireMobile(?string $repertoireMobile): static
    {
        $this->repertoireMobile = $repertoireMobile;

        return $this;
    }

    public function getRepertoireEmail(): ?string
    {
        return $this->repertoireEmail;
    }

    public function setRepertoireEmail(string $repertoireEmail): static
    {
        $this->repertoireEmail = $repertoireEmail;

        return $this;
    }

    public function getRepertoireSiret(): ?string
    {
        return $this->repertoireSiret;
    }

    public function setRepertoireSiret(string $repertoireSiret): static
    {
        $this->repertoireSiret = $repertoireSiret;

        return $this;
    }

    public function getRepertoireNomEntreprise(): ?string
    {
        return $this->repertoireNomEntreprise;
    }

    public function setRepertoireNomEntreprise(?string $repertoireNomEntreprise): static
    {
        $this->repertoireNomEntreprise = $repertoireNomEntreprise;

        return $this;
    }

    public function getRepertoireClient(): ?User
    {
        return $this->repertoireClient;
    }

    public function setRepertoireClient(?User $repertoireClient): static
    {
        $this->repertoireClient = $repertoireClient;

        return $this;
    }

    public function getRepertoireDossier(): ?Dossier
    {
        return $this->repertoireDossier;
    }

    public function setRepertoireDossier(?Dossier $repertoireDossier): static
    {
        $this->repertoireDossier = $repertoireDossier;

        return $this;
    }

    /**
     * @return Collection<int, Contact>
     */
    public function getRepertoireContact(): Collection
    {
        return $this->repertoireContact;
    }

    public function addRepertoireContact(Contact $contact): static
    {
        if (!$this->repertoireContact->contains($contact)) {
            $this->repertoireContact->add($contact);
            $contact->setRepertoire($this);
        }

        return $this;
    }

    public function removeRepertoireContact(Contact $contact): static
    {
        if ($this->repertoireContact->removeElement($contact)) {
            // set the owning side to null (unless already changed)
            if ($contact->getRepertoire() === $this) {
                $contact->setRepertoire(null);
            }
        }

        return $this;
    }
}

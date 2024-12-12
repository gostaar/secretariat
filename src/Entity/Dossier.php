<?php

namespace App\Entity;

use App\Repository\DossierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DossierRepository::class)]
class Dossier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $dossierId = null;  // Remplacement de id par dossierId

    #[ORM\Column(length: 255)]
    private ?string $dossierName = null;  // Remplacement de name par dossierName

    #[ORM\Column(length: 255)]
    private ?string $dossier = null;  // Champ dossier ajouté

    /**
     * @var Collection<int, Repertoire>
     */
    #[ORM\OneToMany(targetEntity: Repertoire::class, mappedBy: 'dossier')]
    private Collection $repertoires;

    /**
     * @var Collection<int, DocumentsUtilisateur>
     */
    #[ORM\OneToMany(targetEntity: DocumentsUtilisateur::class, mappedBy: 'dossier')]
    private Collection $documentsUtilisateurs;

    public function __construct()
    {
        $this->repertoires = new ArrayCollection();
        $this->documentsUtilisateurs = new ArrayCollection();
    }

    public function getDossierId(): ?int
    {
        return $this->dossierId;
    }

    public function setDossierId(int $dossierId): static
    {
        $this->dossierId = $dossierId;

        return $this;
    }

    public function getDossierName(): ?string
    {
        return $this->dossierName;
    }

    public function setDossierName(string $dossierName): static
    {
        $this->dossierName = $dossierName;

        return $this;
    }

    public function getDossier(): ?string
    {
        return $this->dossier;
    }

    public function setDossier(string $dossier): static
    {
        $this->dossier = $dossier;

        return $this;
    }

    /**
     * @return Collection<int, Repertoire>
     */
    public function getRepertoires(): Collection
    {
        return $this->repertoires;
    }

    public function addRepertoire(Repertoire $repertoire): static
    {
        if (!$this->repertoires->contains($repertoire)) {
            $this->repertoires->add($repertoire);
            $repertoire->setDossier($this);
        }

        return $this;
    }

    public function removeRepertoire(Repertoire $repertoire): static
    {
        if ($this->repertoires->removeElement($repertoire)) {
            // set the owning side to null (unless already changed)
            if ($repertoire->getDossier() === $this) {
                $repertoire->setDossier(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DocumentsUtilisateur>
     */
    public function getDocumentsUtilisateurs(): Collection
    {
        return $this->documentsUtilisateurs;
    }

    public function addDocumentsUtilisateur(DocumentsUtilisateur $documentsUtilisateur): static
    {
        if (!$this->documentsUtilisateurs->contains($documentsUtilisateur)) {
            $this->documentsUtilisateurs->add($documentsUtilisateur);
            $documentsUtilisateur->setDossier($this);
        }

        return $this;
    }

    public function removeDocumentsUtilisateur(DocumentsUtilisateur $documentsUtilisateur): static
    {
        if ($this->documentsUtilisateurs->removeElement($documentsUtilisateur)) {
            // set the owning side to null (unless already changed)
            if ($documentsUtilisateur->getDossier() === $this) {
                $documentsUtilisateur->setDossier(null);
            }
        }

        return $this;
    }
}

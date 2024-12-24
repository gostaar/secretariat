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
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'dossiers')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'dossiers')]
    private ?Services $services = null;

    /**
     * @var Collection<int, Repertoire>
     */
    #[ORM\OneToMany(targetEntity: Repertoire::class, mappedBy: 'dossier')]
    private Collection $repertoires;

    /**
     * @var Collection<int, DocumentsUtilisateur>
     */
    #[ORM\OneToMany(targetEntity: DocumentsUtilisateur::class, mappedBy: 'dossier')]
    private Collection $documents;

    public function __construct()
    {
        $this->repertoires = new ArrayCollection();
        $this->documents = new ArrayCollection();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'user' => $this->getUser(),
            'services' => $this->getServices(),
            'repertoires' => $this->getRepertoires(),
            'documents' => $this->getDocuments(),
        ];
    }

    public function __toString(): string
    {
        return $this->name;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getServices(): ?Services
    {
        return $this->services;
    }

    public function setServices(?Services $services): static
    {
        $this->services = $services;

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
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(DocumentsUtilisateur $document): static
    {
        if (!$this->documents->contains($document)) {
            $this->documents->add($document);
            $document->setDossier($this);
        }

        return $this;
    }

    public function removeDocument(DocumentsUtilisateur $document): static
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getDossier() === $this) {
                $document->setDossier(null);
            }
        }

        return $this;
    }


}

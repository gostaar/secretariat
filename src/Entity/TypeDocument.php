<?php

namespace App\Entity;

use App\Repository\TypeDocumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeDocumentRepository::class)]
class TypeDocument
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $typeDocumentId = null;

    #[ORM\Column(length: 255)]
    private ?string $typeDocumentName = null;

    /**
     * @var Collection<int, DocumentsUtilisateur>
     */
    #[ORM\OneToMany(targetEntity: DocumentsUtilisateur::class, mappedBy: 'type_document')]
    private Collection $typeDocumentDocumentsUtilisateurs;

    public function __construct()
    {
        $this->typeDocumentDocumentsUtilisateurs = new ArrayCollection();
    }

    public function getTypeDocumentId(): ?int
    {
        return $this->typeDocumentId;
    }

    public function getTypeDocumentName(): ?string
    {
        return $this->typeDocumentName;
    }

    public function setTypeDocumentName(string $typeDocumentName): static
    {
        $this->typeDocumentName = $typeDocumentName;

        return $this;
    }

    /**
     * @return Collection<int, DocumentsUtilisateur>
     */
    public function getTypeDocumentDocumentsUtilisateurs(): Collection
    {
        return $this->typeDocumentDocumentsUtilisateurs;
    }

    public function addTypeDocumentDocumentsUtilisateur(DocumentsUtilisateur $documentsUtilisateur): static
    {
        if (!$this->typeDocumentDocumentsUtilisateurs->contains($documentsUtilisateur)) {
            $this->typeDocumentDocumentsUtilisateurs->add($documentsUtilisateur);
            $documentsUtilisateur->setTypeDocument($this);
        }

        return $this;
    }

    public function removeTypeDocumentDocumentsUtilisateur(DocumentsUtilisateur $documentsUtilisateur): static
    {
        if ($this->typeDocumentDocumentsUtilisateurs->removeElement($documentsUtilisateur)) {
            // set the owning side to null (unless already changed)
            if ($documentsUtilisateur->getTypeDocument() === $this) {
                $documentsUtilisateur->setTypeDocument(null);
            }
        }

        return $this;
    }
}

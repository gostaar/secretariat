<?php

namespace App\Entity;

use App\Repository\DocumentsUtilisateurRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentsUtilisateurRepository::class)]
class DocumentsUtilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_document = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;
    
    #[ORM\Column(length: 255)]
    private ?string $expediteur = null;
    
    #[ORM\Column(length: 255)]
    private ?string $destinataire = null;
    
    #[ORM\Column(length: 255)]
    private ?string $file_path = null;

    #[ORM\Column]
    private ?bool $is_active = null;

    #[ORM\Column(length: 255)]
    private ?string $details = null;

    #[ORM\ManyToOne(inversedBy: 'documents')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'documents')]
    private ?Dossier $dossier = null;

    #[ORM\ManyToOne(inversedBy: 'documents')]
    private ?TypeDocument $typeDocument = null;

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'date_document' => $this->getDateDocument(),
            'name' => $this->getName(),
            'expediteur' => $this->getExpediteur(),
            'destinataire' => $this->getDestinataire(),
            'file_path' => $this->getFilePath(),
            'is_active' => $this->isActive(),
            'details' => $this->getDetails(),
            'user' => $this->getUser(),
            'dossier' => $this->getDossier(),
            'typeDocument' => $this->getTypeDocument(),
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

    public function getDateDocument(): ?\DateTimeInterface
    {
        return $this->date_document;
    }

    public function setDateDocument(\DateTimeInterface $date_document): static
    {
        $this->date_document = $date_document;

        return $this;
    }

    public function getExpediteur(): ?string
    {
        return $this->expediteur;
    }

    public function setExpediteur(string $expediteur): static
    {
        $this->expediteur = $expediteur;

        return $this;
    }

    public function getDestinataire(): ?string
    {
        return $this->destinataire;
    }

    public function setDestinataire(string $destinataire): static
    {
        $this->destinataire = $destinataire;

        return $this;
    }

    public function getFilePath(): ?string
    {
        return $this->file_path;
    }

    public function setFilePath(string $file_path): static
    {
        $this->file_path = $file_path;

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

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(string $details): static
    {
        $this->details = $details;

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

    public function getDossier(): ?Dossier
    {
        return $this->dossier;
    }

    public function setDossier(?Dossier $dossier): static
    {
        $this->dossier = $dossier;

        return $this;
    }

    public function getTypeDocument(): ?TypeDocument
    {
        return $this->typeDocument;
    }

    public function setTypeDocument(?TypeDocument $typeDocument): static
    {
        $this->typeDocument = $typeDocument;

        return $this;
    }

}

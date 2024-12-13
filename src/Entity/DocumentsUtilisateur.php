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

    #[ORM\ManyToOne(inversedBy: 'documentsUtilisateurs')]
    private ?TypeDocument $type_document = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_document = null;

    #[ORM\ManyToOne(inversedBy: 'documentsUtilisateurs')]
    private ?Dossier $dossier = null;

    #[ORM\Column(length: 255)]
    private ?string $expediteur = null;

    #[ORM\Column(length: 255)]
    private ?string $destinataire = null;

    #[ORM\ManyToOne(inversedBy: 'documentsUtilisateurs')]
    private ?Services $service = null;

    #[ORM\Column(length: 255)]
    private ?string $file_path = null;

    #[ORM\ManyToOne(inversedBy: 'documentsUtilisateurs')]
    private ?User $client = null;

    #[ORM\Column]
    private ?bool $is_active = null;

    #[ORM\Column(length: 255)]
    private ?string $details = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeDocument(): ?TypeDocument
    {
        return $this->type_document;
    }

    public function setTypeDocument(?TypeDocument $type_document): static
    {
        $this->type_document = $type_document;

        return $this;
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

    public function getDossier(): ?Dossier
    {
        return $this->dossier;
    }

    public function setDossier(?Dossier $dossier): static
    {
        $this->dossier = $dossier;

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

    public function getService(): ?Services
    {
        return $this->service;
    }

    public function setService(?Services $service): static
    {
        $this->service = $service;

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

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->is_active;
    }

    public function setActiv(bool $is_active): static
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
}

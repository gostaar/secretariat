<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $contactId = null;

    #[ORM\Column(length: 255)]
    private ?string $contactNom = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $contactTelephone = null;

    #[ORM\Column(length: 180, nullable: true)]
    private ?string $contactEmail = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $contactRole = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contactCommentaire = null;

    #[ORM\ManyToOne(inversedBy: 'contact')]
    private ?Repertoire $contactRepertoire = null;

    public function getContactId(): ?int
    {
        return $this->contactId;
    }

    public function getContactNom(): ?string
    {
        return $this->contactNom;
    }

    public function setContactNom(string $contactNom): static
    {
        $this->contactNom = $contactNom;

        return $this;
    }

    public function getContactTelephone(): ?string
    {
        return $this->contactTelephone;
    }

    public function setContactTelephone(?string $contactTelephone): static
    {
        $this->contactTelephone = $contactTelephone;

        return $this;
    }

    public function getContactEmail(): ?string
    {
        return $this->contactEmail;
    }

    public function setContactEmail(?string $contactEmail): static
    {
        $this->contactEmail = $contactEmail;

        return $this;
    }

    public function getContactRole(): ?string
    {
        return $this->contactRole;
    }

    public function setContactRole(?string $contactRole): static
    {
        $this->contactRole = $contactRole;

        return $this;
    }

    public function getContactCommentaire(): ?string
    {
        return $this->contactCommentaire;
    }

    public function setContactCommentaire(?string $contactCommentaire): static
    {
        $this->contactCommentaire = $contactCommentaire;

        return $this;
    }

    public function getContactRepertoire(): ?Repertoire
    {
        return $this->contactRepertoire;
    }

    public function setContactRepertoire(?Repertoire $contactRepertoire): static
    {
        $this->contactRepertoire = $contactRepertoire;

        return $this;
    }
}

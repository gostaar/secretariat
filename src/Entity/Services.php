<?php

namespace App\Entity;

use App\Repository\ServicesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServicesRepository::class)]
class Services
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, DocumentsUtilisateur>
     */
    #[ORM\OneToMany(targetEntity: DocumentsUtilisateur::class, mappedBy: 'service')]
    private Collection $documentsUtilisateurs;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'servicesSouscrits')]
    private Collection $users;

    public function __construct()
    {
        $this->documentsUtilisateurs = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $documentsUtilisateur->setService($this);
        }

        return $this;
    }

    public function removeDocumentsUtilisateur(DocumentsUtilisateur $documentsUtilisateur): static
    {
        if ($this->documentsUtilisateurs->removeElement($documentsUtilisateur)) {
            // set the owning side to null (unless already changed)
            if ($documentsUtilisateur->getService() === $this) {
                $documentsUtilisateur->setService(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addServicesSouscrit($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeServicesSouscrit($this);
        }

        return $this;
    }
}

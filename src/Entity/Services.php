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
    private ?int $serviceId = null;

    #[ORM\Column(length: 255)]
    private ?string $serviceName = null;

    /**
     * @var Collection<int, DocumentsUtilisateur>
     */
    #[ORM\OneToMany(targetEntity: DocumentsUtilisateur::class, mappedBy: 'service')]
    private Collection $serviceDocumentsUtilisateurs;

    public function __construct()
    {
        $this->serviceDocumentsUtilisateurs = new ArrayCollection();
    }

    public function getServiceId(): ?int
    {
        return $this->serviceId;
    }

    public function getServiceName(): ?string
    {
        return $this->serviceName;
    }

    public function setServiceName(string $serviceName): static
    {
        $this->serviceName = $serviceName;

        return $this;
    }

    /**
     * @return Collection<int, DocumentsUtilisateur>
     */
    public function getServiceDocumentsUtilisateurs(): Collection
    {
        return $this->serviceDocumentsUtilisateurs;
    }

    public function addServiceDocumentsUtilisateur(DocumentsUtilisateur $documentsUtilisateur): static
    {
        if (!$this->serviceDocumentsUtilisateurs->contains($documentsUtilisateur)) {
            $this->serviceDocumentsUtilisateurs->add($documentsUtilisateur);
            $documentsUtilisateur->setService($this);
        }

        return $this;
    }

    public function removeServiceDocumentsUtilisateur(DocumentsUtilisateur $documentsUtilisateur): static
    {
        if ($this->serviceDocumentsUtilisateurs->removeElement($documentsUtilisateur)) {
            // set the owning side to null (unless already changed)
            if ($documentsUtilisateur->getService() === $this) {
                $documentsUtilisateur->setService(null);
            }
        }

        return $this;
    }
}

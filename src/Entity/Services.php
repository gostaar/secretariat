<?php

namespace App\Entity;

use App\Repository\ServicesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;

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
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'services')]
    private Collection $users;

    /**
     * @var Collection<int, Events>
     */
    #[ORM\OneToMany(targetEntity: Events::class, mappedBy: 'services')]
    private Collection $events;

    /**
     * @var Collection<int, Dossier>
     */
    #[ORM\OneToMany(targetEntity: Dossier::class, mappedBy: 'services')]
    private Collection $dossiers;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->dossiers = new ArrayCollection();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'users' => $this->getUsers(),
            'events' => $this->getEvents(),
            'dossiers' => $this->getDossiers(),
        ];
    }

    public function __toString(): string
    {
        return $this->name; // Retourne le nom du dossier par exemple
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

    public const AVAILABLE_SERVICES = [
        'Administratif',
        'Commercial',
        'Numérique',
        'Agenda',
        'Téléphonique',
        'Repertoire',
        'Espace Personnel'
    ];

    public static function getAvailableServices(): array
    {
        return array_combine(self::AVAILABLE_SERVICES, self::AVAILABLE_SERVICES);
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
            $user->addService($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeService($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Events>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Events $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setServices($this);
        }

        return $this;
    }

    public function removeEvent(Events $event): static
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getServices() === $this) {
                $event->setServices(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Dossier>
     */
    public function getDossiers(): Collection
    {
        return $this->dossiers;
    }

    public function addDossier(Dossier $dossier): static
    {
        if (!$this->dossiers->contains($dossier)) {
            $this->dossiers->add($dossier);
            $dossier->setServices($this);
        }

        return $this;
    }

    public function removeDossier(Dossier $dossier): static
    {
        if ($this->dossiers->removeElement($dossier)) {
            // set the owning side to null (unless already changed)
            if ($dossier->getServices() === $this) {
                $dossier->setServices(null);
            }
        }

        return $this;
    }

}

<?php

namespace App\Entity;

use App\Repository\DepositRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DepositRepository::class)]
class Deposit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(type: Types::FLOAT)]
    private ?float $latitude = null;

    #[ORM\Column(type: Types::FLOAT)]
    private ?float $longitude = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $schedules = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'deposit')]
    private Collection $owner;

    // /**
    //  * @var Collection<int, Client>
    //  */
    // #[ORM\OneToMany(targetEntity: Client::class, mappedBy: 'deposit', orphanRemoval: true)]
    // private Collection $clients;

    public function __construct()
    {
        $this->owner = new ArrayCollection();
        // $this->clients = new ArrayCollection();
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSchedules(): ?string
    {
        return $this->schedules;
    }

    public function setSchedules(?string $schedules): static
    {
        $this->schedules = $schedules;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getOwner(): Collection
    {
        return $this->owner;
    }

    public function addOwner(User $owner): static
    {
        if (!$this->owner->contains($owner)) {
            $this->owner->add($owner);
            $owner->setDeposit($this);
        }

        return $this;
    }

    public function removeOwner(User $owner): static
    {
        if ($this->owner->removeElement($owner)) {
            if ($owner->getDeposit() === $this) {
                $owner->setDeposit(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }

    // /**
    //  * @return Collection<int, Client>
    //  */
    // public function getClients(): Collection
    // {
    //     return $this->clients;
    // }

    // public function addClient(Client $client): static
    // {
    //     if (!$this->clients->contains($client)) {
    //         $this->clients->add($client);
    //         $client->setDeposit($this);
    //     }

    //     return $this;
    // }

    // public function removeClient(Client $client): static
    // {
    //     if ($this->clients->removeElement($client)) {
    //         // set the owning side to null (unless already changed)
    //         if ($client->getDeposit() === $this) {
    //             $client->setDeposit(null);
    //         }
    //     }

    //     return $this;
    // }
}
<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: "L'email ne peut pas être vide.")]
    #[Assert\Email(message: "Veuillez fournir une adresse email valide.")]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $trackingNumber = null;

    #[ORM\ManyToOne(inversedBy: 'clients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Deposit $deposit = null;

    /**
     * @var Collection<int, DeviceMaintenance>
     */
    #[ORM\OneToMany(targetEntity: DeviceMaintenance::class, mappedBy: 'trackingNumber', orphanRemoval: true)]
    private Collection $deviceMaintenances;

    public function __construct()
    {
        $this->deviceMaintenances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;
        return $this;
    }

    #[ORM\PrePersist]
    public function generateTrackingNumber(): void
    {
        if (!$this->trackingNumber) {
            $this->trackingNumber = strtoupper(Uuid::v4()); // Génère un UUID unique
        }
    }

    public function getTrackingNumber(): ?string
    {
        return $this->trackingNumber;
    }

    public function setTrackingNumber(string $trackingNumber): self
    {
        $this->trackingNumber = $trackingNumber;
        return $this;
    }

    public function getDeposit(): ?Deposit
    {
        return $this->deposit;
    }

    public function setDeposit(?Deposit $deposit): self
    {
        $this->deposit = $deposit;
        return $this;
    }

    /**
     * @return Collection<int, DeviceMaintenance>
     */
    public function getDeviceMaintenances(): Collection
    {
        return $this->deviceMaintenances;
    }

    public function addDeviceMaintenance(DeviceMaintenance $deviceMaintenance): self
    {
        if (!$this->deviceMaintenances->contains($deviceMaintenance)) {
            $this->deviceMaintenances[] = $deviceMaintenance;
            $deviceMaintenance->setTrackingNumber($this);
        }

        return $this;
    }

    public function removeDeviceMaintenance(DeviceMaintenance $deviceMaintenance): self
    {
        if ($this->deviceMaintenances->removeElement($deviceMaintenance)) {
            // set the owning side to null (unless already changed)
            if ($deviceMaintenance->getTrackingNumber() === $this) {
                $deviceMaintenance->setTrackingNumber(null);
            }
        }

        return $this;
    }
}

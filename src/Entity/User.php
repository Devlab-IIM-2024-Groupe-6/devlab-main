<?php
namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\ManyToOne(inversedBy: 'owner')]
    private ?Deposit $deposit = null;

    #[ORM\OneToMany(targetEntity: DeviceMaintenance::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $deviceMaintenances;

    #[ORM\Column(length: 255, nullable: true, unique: true)]
    private ?string $trackingNumber = null;

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

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        return array_unique($this->roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials(): void
    {
        // Clear temporary, sensitive data if any
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getDeposit(): ?Deposit
    {
        return $this->deposit;
    }

    public function setDeposit(?Deposit $deposit): static
    {
        $this->deposit = $deposit;

        return $this;
    }

    public function getDeviceMaintenances(): Collection
    {
        return $this->deviceMaintenances;
    }

    public function addDeviceMaintenance(DeviceMaintenance $deviceMaintenance): static
    {
        if (!$this->deviceMaintenances->contains($deviceMaintenance)) {
            $this->deviceMaintenances->add($deviceMaintenance);
            $deviceMaintenance->setUser($this);
        }

        return $this;
    }

    public function removeDeviceMaintenance(DeviceMaintenance $deviceMaintenance): static
    {
        if ($this->deviceMaintenances->removeElement($deviceMaintenance)) {
            if ($deviceMaintenance->getUser() === $this) {
                $deviceMaintenance->setUser(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function getTrackingNumber(): ?string
    {
        return $this->trackingNumber;
    }

    public function setTrackingNumber(?string $trackingNumber): static
    {
        $this->trackingNumber = $trackingNumber;

        return $this;
    }

    #[ORM\PrePersist]
    public function generateTrackingNumber(): void
    {
        if (empty($this->trackingNumber)) {
            $this->trackingNumber = $this->generateRandomTrackingNumber();
        }
    }

    private function generateRandomTrackingNumber(): string
    {
        return strtoupper(bin2hex(random_bytes(4))) . '-' . random_int(1000, 9999);
    }
}
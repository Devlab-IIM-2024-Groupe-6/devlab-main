<?php
namespace App\Entity;

use App\Repository\DeviceMaintenanceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeviceMaintenanceRepository::class)]
class DeviceMaintenance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'deviceMaintenances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $trackingNumber = null;

    #[ORM\Column]
    private ?int $currentStep = null;

    #[ORM\Column]
    private ?bool $screen = null;

    #[ORM\Column]
    private ?bool $oxidationStatus = null;

    #[ORM\Column]
    private ?bool $hinges = null;

    #[ORM\Column]
    private ?bool $fan = null;

    #[ORM\Column]
    private ?bool $button = null;

    #[ORM\Column]
    private ?bool $sensors = null;

    #[ORM\Column]
    private ?bool $chassis = null;

    #[ORM\Column]
    private ?bool $dataWipe = null;

    #[ORM\Column]
    private ?bool $computerUnlock = null;

    #[ORM\Column]
    private ?bool $driver = null;

    #[ORM\Column]
    private ?bool $computerUpdate = null;

    #[ORM\Column]
    private ?bool $motherboard = null;

    #[ORM\Column]
    private ?bool $networks = null;

    #[ORM\Column]
    private ?bool $components = null;

    #[ORM\Column]
    private ?bool $battery = null;

    #[ORM\Column]
    private ?bool $powerSupply = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTrackingNumber(): ?string
    {
        return $this->trackingNumber;
    }

    public function setTrackingNumber(string $trackingNumber): static
    {
        $this->trackingNumber = $trackingNumber;

        return $this;
    }

    public function getCurrentStep(): ?int
    {
        return $this->currentStep;
    }

    public function setCurrentStep(int $currentStep): static
    {
        $this->currentStep = $currentStep;

        return $this;
    }

    public function isScreen(): ?bool
    {
        return $this->screen;
    }

    public function setScreen(bool $screen): static
    {
        $this->screen = $screen;

        return $this;
    }

    public function isOxidationStatus(): ?bool
    {
        return $this->oxidationStatus;
    }

    public function setOxidationStatus(bool $oxidationStatus): static
    {
        $this->oxidationStatus = $oxidationStatus;

        return $this;
    }

    public function isHinges(): ?bool
    {
        return $this->hinges;
    }

    public function setHinges(bool $hinges): static
    {
        $this->hinges = $hinges;

        return $this;
    }

    public function isFan(): ?bool
    {
        return $this->fan;
    }

    public function setFan(bool $fan): static
    {
        $this->fan = $fan;

        return $this;
    }

    public function isButton(): ?bool
    {
        return $this->button;
    }

    public function setButton(bool $button): static
    {
        $this->button = $button;

        return $this;
    }

    public function isSensors(): ?bool
    {
        return $this->sensors;
    }

    public function setSensors(bool $sensors): static
    {
        $this->sensors = $sensors;

        return $this;
    }

    public function isChassis(): ?bool
    {
        return $this->chassis;
    }

    public function setChassis(bool $chassis): static
    {
        $this->chassis = $chassis;

        return $this;
    }

    public function isDataWipe(): ?bool
    {
        return $this->dataWipe;
    }

    public function setDataWipe(bool $dataWipe): static
    {
        $this->dataWipe = $dataWipe;

        return $this;
    }

    public function isComputerUnlock(): ?bool
    {
        return $this->computerUnlock;
    }

    public function setComputerUnlock(bool $computerUnlock): static
    {
        $this->computerUnlock = $computerUnlock;

        return $this;
    }

    public function isDriver(): ?bool
    {
        return $this->driver;
    }

    public function setDriver(bool $driver): static
    {
        $this->driver = $driver;

        return $this;
    }

    public function isComputerUpdate(): ?bool
    {
        return $this->computerUpdate;
    }

    public function setComputerUpdate(bool $computerUpdate): static
    {
        $this->computerUpdate = $computerUpdate;

        return $this;
    }

    public function isMotherboard(): ?bool
    {
        return $this->motherboard;
    }

    public function setMotherboard(bool $motherboard): static
    {
        $this->motherboard = $motherboard;

        return $this;
    }

    public function isNetworks(): ?bool
    {
        return $this->networks;
    }

    public function setNetworks(bool $networks): static
    {
        $this->networks = $networks;

        return $this;
    }

    public function isComponents(): ?bool
    {
        return $this->components;
    }

    public function setComponents(bool $components): static
    {
        $this->components = $components;

        return $this;
    }

    public function isBattery(): ?bool
    {
        return $this->battery;
    }

    public function setBattery(bool $battery): static
    {
        $this->battery = $battery;

        return $this;
    }

    public function isPowerSupply(): ?bool
    {
        return $this->powerSupply;
    }

    public function setPowerSupply(bool $powerSupply): static
    {
        $this->powerSupply = $powerSupply;

        return $this;
    }
}
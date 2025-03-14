<?php

namespace App\Entity;

use App\Entity\Client;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use App\Repository\DeviceMaintenanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\OrderBy;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DeviceMaintenanceRepository::class)]
#[ORM\HasLifecycleCallbacks]
class DeviceMaintenance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(targetEntity: DeviceMaintenanceLog::class, mappedBy: 'deviceMaintenance', cascade: ['persist', 'remove'])]
    #[OrderBy(['changedAt' => 'DESC'])]
    private Collection $maintenanceLogs;

    #[ORM\Column(nullable: true)]
    private ?bool $screen = null;

    #[ORM\Column(nullable: true)]
    private ?bool $oxidationStatus = null;

    #[ORM\Column(nullable: true)]
    private ?bool $hinges = null;

    #[ORM\Column(nullable: true)]
    private ?bool $fan = null;

    #[ORM\Column(nullable: true)]
    private ?bool $button = null;

    #[ORM\Column(nullable: true)]
    private ?bool $sensors = null;

    #[ORM\Column(nullable: true)]
    private ?bool $chassis = null;

    #[ORM\Column(nullable: true)]
    private ?bool $dataWipe = null;

    #[ORM\Column(nullable: true)]
    private ?bool $computerUnlock = null;

    #[ORM\Column(nullable: true)]
    private ?bool $driver = null;

    #[ORM\Column(nullable: true)]
    private ?bool $computerUpdate = null;

    #[ORM\Column(nullable: true)]
    private ?bool $motherboard = null;

    #[ORM\Column(nullable: true)]
    private ?bool $networks = null;

    #[ORM\Column(nullable: true)]
    private ?bool $components = null;

    #[ORM\Column(nullable: true)]
    private ?bool $battery = null;

    #[ORM\Column(nullable: true)]
    private ?bool $powerSupply = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $trackingNumber = null;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'deviceMaintenances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\ManyToOne(targetEntity: Deposit::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Deposit $deposit = null;

    public function __construct()
    {
        $this->maintenanceLogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isScreen(): ?bool
    {
        return $this->screen;
    }

    public function setScreen(?bool $screen): static
    {
        $this->screen = $screen;
        return $this;
    }

    public function isOxidationStatus(): ?bool
    {
        return $this->oxidationStatus;
    }

    public function setOxidationStatus(?bool $oxidationStatus): static
    {
        $this->oxidationStatus = $oxidationStatus;
        return $this;
    }

    public function isHinges(): ?bool
    {
        return $this->hinges;
    }

    public function setHinges(?bool $hinges): static
    {
        $this->hinges = $hinges;
        return $this;
    }

    public function isFan(): ?bool
    {
        return $this->fan;
    }

    public function setFan(?bool $fan): static
    {
        $this->fan = $fan;
        return $this;
    }

    public function isButton(): ?bool
    {
        return $this->button;
    }

    public function setButton(?bool $button): static
    {
        $this->button = $button;
        return $this;
    }

    public function isSensors(): ?bool
    {
        return $this->sensors;
    }

    public function setSensors(?bool $sensors): static
    {
        $this->sensors = $sensors;
        return $this;
    }

    public function isChassis(): ?bool
    {
        return $this->chassis;
    }

    public function setChassis(?bool $chassis): static
    {
        $this->chassis = $chassis;
        return $this;
    }

    public function isDataWipe(): ?bool
    {
        return $this->dataWipe;
    }

    public function setDataWipe(?bool $dataWipe): static
    {
        $this->dataWipe = $dataWipe;
        return $this;
    }

    public function isComputerUnlock(): ?bool
    {
        return $this->computerUnlock;
    }

    public function setComputerUnlock(?bool $computerUnlock): static
    {
        $this->computerUnlock = $computerUnlock;
        return $this;
    }

    public function isDriver(): ?bool
    {
        return $this->driver;
    }

    public function setDriver(?bool $driver): static
    {
        $this->driver = $driver;
        return $this;
    }

    public function isComputerUpdate(): ?bool
    {
        return $this->computerUpdate;
    }

    public function setComputerUpdate(?bool $computerUpdate): static
    {
        $this->computerUpdate = $computerUpdate;
        return $this;
    }

    public function isMotherboard(): ?bool
    {
        return $this->motherboard;
    }

    public function setMotherboard(?bool $motherboard): static
    {
        $this->motherboard = $motherboard;
        return $this;
    }

    public function isNetworks(): ?bool
    {
        return $this->networks;
    }

    public function setNetworks(?bool $networks): static
    {
        $this->networks = $networks;
        return $this;
    }

    public function isComponents(): ?bool
    {
        return $this->components;
    }

    public function setComponents(?bool $components): static
    {
        $this->components = $components;
        return $this;
    }

    public function isBattery(): ?bool
    {
        return $this->battery;
    }

    public function setBattery(?bool $battery): static
    {
        $this->battery = $battery;
        return $this;
    }

    public function isPowerSupply(): ?bool
    {
        return $this->powerSupply;
    }

    public function setPowerSupply(?bool $powerSupply): static
    {
        $this->powerSupply = $powerSupply;
        return $this;
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
        if (!$this->trackingNumber) {
            $this->trackingNumber = strtoupper(bin2hex(random_bytes(4))) . '-' . random_int(1000, 9999);
        }
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;
        return $this;
    }

    public function getMaintenanceLogs(): Collection
    {
        return $this->maintenanceLogs;
    }

    public function addMaintenanceLog(DeviceMaintenanceLog $log): static
    {
        if (!$this->maintenanceLogs->contains($log)) {
            $this->maintenanceLogs->add($log);
            $log->setDeviceMaintenance($this);
        }
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

    public function getLatestMaintenanceLog(): ?DeviceMaintenanceLog
    {
        if ($this->maintenanceLogs->isEmpty()) {
            return null;
        }

        // Tri par date décroissante et récupération du premier élément
        $logs = $this->maintenanceLogs->toArray();
        usort($logs, fn($a, $b) => $b->getChangedAt() <=> $a->getChangedAt());
    
        return $logs[0] ?? null;
    }
}

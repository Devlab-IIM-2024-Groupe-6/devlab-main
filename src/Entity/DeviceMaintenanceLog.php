<?php

namespace App\Entity;

use App\Repository\DeviceMaintenanceLogRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeviceMaintenanceLogRepository::class)]
class DeviceMaintenanceLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: DeviceMaintenance::class, inversedBy: 'maintenanceLogs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?DeviceMaintenance $deviceMaintenance = null;

    #[ORM\ManyToOne(targetEntity: MaintenanceStep::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?MaintenanceStep $previousStep = null;

    #[ORM\ManyToOne(targetEntity: MaintenanceStep::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?MaintenanceStep $currentStep = null;

    #[ORM\ManyToOne(targetEntity: MaintenanceStep::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?MaintenanceStep $nextStep = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $changedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeviceMaintenance(): ?DeviceMaintenance
    {
        return $this->deviceMaintenance;
    }

    public function setDeviceMaintenance(?DeviceMaintenance $deviceMaintenance): static
    {
        $this->deviceMaintenance = $deviceMaintenance;
        return $this;
    }

    public function getPreviousStep(): ?MaintenanceStep
    {
        return $this->previousStep;
    }

    public function setPreviousStep(?MaintenanceStep $previousStep): static
    {
        $this->previousStep = $previousStep;
        return $this;
    }

    public function getCurrentStep(): ?MaintenanceStep
    {
        return $this->currentStep;
    }

    public function setCurrentStep(?MaintenanceStep $currentStep): static
    {
        $this->currentStep = $currentStep;
        return $this;
    }

    public function getNextStep(): ?MaintenanceStep
    {
        return $this->nextStep;
    }

    public function setNextStep(?MaintenanceStep $nextStep): static
    {
        $this->nextStep = $nextStep;
        return $this;
    }

    public function getChangedAt(): ?\DateTimeInterface
    {
        return $this->changedAt;
    }

    public function setChangedAt(\DateTimeInterface $changedAt): static
    {
        $this->changedAt = $changedAt;
        return $this;
    }
}

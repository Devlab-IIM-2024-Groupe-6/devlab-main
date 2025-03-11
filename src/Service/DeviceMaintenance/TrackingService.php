<?php

namespace App\Service\DeviceMaintenance;

use App\Entity\DeviceMaintenance;
use Doctrine\ORM\EntityManagerInterface;

class TrackingService
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    public function validateTrackingNumber(string $trackingNumber): bool
    {
        $deviceMaintenance = $this->em->getRepository(DeviceMaintenance::class)
            ->findOneBy(['trackingNumber' => $trackingNumber]);

        return $deviceMaintenance && $deviceMaintenance->getClient();
    }

    public function getTrackingResult(string $trackingNumber): ?array
    {
        $deviceMaintenance = $this->em->getRepository(DeviceMaintenance::class)
            ->findOneBy(['trackingNumber' => $trackingNumber]);

        if (!$deviceMaintenance || !$client = $deviceMaintenance->getClient()) {
            return null;
        }

        $latestLog = $deviceMaintenance->getLatestMaintenanceLog();
        $currentStep = $latestLog?->getCurrentStep();

        return [
            'client' => $client,
            'deposit' => $deviceMaintenance->getDeposit(),
            'deviceMaintenance' => $deviceMaintenance,
            'currentStep' => $currentStep,
            'latestLog' => $latestLog
        ];
    }
}

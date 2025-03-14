<?php

namespace App\Service\DeviceMaintenance;

use App\Entity\DeviceMaintenance;
use App\Entity\DeviceMaintenanceLog;
use App\Entity\MaintenanceStep;
use Doctrine\ORM\EntityManagerInterface;

class DeviceMaintenanceWorkflowService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * Crée le premier log pour un DeviceMaintenance donné.
     *  - currentStep : Step dont stepOrder = 0
     *  - nextStep    : Step dont stepOrder = 1 (si existe)
     */
    public function createInitialLog(DeviceMaintenance $deviceMaintenance): DeviceMaintenanceLog
    {
        // Récupération du repository
        $stepRepo = $this->entityManager->getRepository(MaintenanceStep::class);

        // Trouver la step 0 (Dépôt du formulaire)
        $currentStep = $stepRepo->findOneBy(['stepOrder' => 1]);
        // Trouver la step 1 (Dépôt du matériel) comme next
        $nextStep    = $stepRepo->findOneBy(['stepOrder' => 2]);

        // Créer le log
        $log = new DeviceMaintenanceLog();
        $log->setDeviceMaintenance($deviceMaintenance);
        $log->setPreviousStep(null);
        $log->setCurrentStep($currentStep);
        $log->setNextStep($nextStep);
        $log->setChangedAt(new \DateTime());

        $this->entityManager->persist($log);

        // À vous de décider si vous faites le flush ici ou dans le contrôleur
        // Pour plus de flexibilité, on peut NE PAS flusher ici
        // $this->entityManager->flush();

        return $log;
    }

    /**
     * Exemple : Avancer d'une step (optionnel)
     * Passer de stepOrder = n (current) à stepOrder = n+1
     * - Créer un nouveau DeviceMaintenanceLog
     * - previous = step(n)
     * - current = step(n+1)
     * - next    = step(n+2) (si existe)
     */
    public function advanceStep(DeviceMaintenance $deviceMaintenance, int $fromOrder): ?DeviceMaintenanceLog
    {
        // Vérifier si on est déjà sur la dernière étape :
        if ($this->isFinalStep($fromOrder)) {
            return null;
        }

        $stepRepo = $this->entityManager->getRepository(MaintenanceStep::class);

        $previousStep = $stepRepo->findOneBy(['stepOrder' => $fromOrder]);
        $currentStep  = $stepRepo->findOneBy(['stepOrder' => $fromOrder + 1]);
        $nextStep     = $stepRepo->findOneBy(['stepOrder' => $fromOrder + 2]);

        $log = new DeviceMaintenanceLog();
        $log->setDeviceMaintenance($deviceMaintenance);
        $log->setPreviousStep($previousStep);
        $log->setCurrentStep($currentStep);
        $log->setNextStep($nextStep);
        $log->setChangedAt(new \DateTime());

        $this->entityManager->persist($log);
        // $this->entityManager->flush(); // à vous de voir si vous flushez ici

        return $log;
    }

    /**
     * Retourne la valeur la plus élevée de stepOrder en base.
     * Si aucune step, on renvoie 0 (ou autre valeur par défaut).
     */
    private function getMaxStepOrder(): int
    {
        $repo = $this->entityManager->getRepository(MaintenanceStep::class);

        // On récupère la step ayant le plus grand stepOrder
        $lastStep = $repo->findOneBy([], ['stepOrder' => 'DESC']);
        if (!$lastStep) {
            return 0;
        }

        return $lastStep->getStepOrder();
    }

    /**
     * Indique si l'étape $stepOrder est déjà l'étape finale
     * (c.-à-d. si $stepOrder >= getMaxStepOrder()).
     */
    public function isFinalStep(int $stepOrder): bool
    {
        $maxOrder = $this->getMaxStepOrder();
        return $stepOrder >= $maxOrder;
    }
}

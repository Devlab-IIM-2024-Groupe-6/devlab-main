<?php

namespace App\Controller\Admin;

use App\Entity\DeviceMaintenance;
use App\Entity\User;
use App\Service\DeviceMaintenance\DeviceMaintenanceWorkflowService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDepotController extends AbstractController
{
    #[Route('/admin/depot', name: 'admin_depot')]
    #[IsGranted('ROLE_ADMIN_POINT_DEPOT')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user || !$user->getDeposit()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas associé à un dépôt.');
        }

        $deposit = $user->getDeposit();
        $depositName = $deposit->getName();

        $repository = $entityManager->getRepository(DeviceMaintenance::class);
        $deviceMaintenances = $repository->findBy(['deposit' => $deposit], ['deposit' => 'ASC']);

        $devicesByDeposit = [];
        foreach ($deviceMaintenances as $device) {
            if ($device->getMaintenanceLogs()->count() > 0 && $device->getMaintenanceLogs()[0]->getCurrentStep()->getStepOrder() === 1) {
                $devicesByDeposit[$depositName][] = $device;
            }
        }

        return $this->render('admin/depot/dashboard.html.twig', [
            'devicesByDeposit' => $devicesByDeposit,
        ]);
    }

    #[Route('/admin/depot/{id}/next', name: 'admin_depot_next_step', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN_POINT_DEPOT')]
    public function advanceNextStep(
        DeviceMaintenance $deviceMaintenance,
        EntityManagerInterface $entityManager,
        DeviceMaintenanceWorkflowService $deviceMaintenanceWorkflow
    ): Response {
        try {
            $maintenanceLogs = $deviceMaintenance->getMaintenanceLogs();
            if (empty($maintenanceLogs) || empty($maintenanceLogs[0]->getCurrentStep())) {
                $this->addFlash('error', 'Aucun état actuel de maintenance trouvé.');
                return $this->redirectToRoute('admin_depot');
            }

            $currentStep = $maintenanceLogs[0]->getCurrentStep();
            $stepOrder = $currentStep->getStepOrder();

            if ($stepOrder >= 2) {
                $this->addFlash('error', "Vous n'êtes pas autorisé à faire cette action.");
                return $this->redirectToRoute('admin_depot');
            }

            $deviceMaintenanceWorkflow->advanceStep($deviceMaintenance, $stepOrder);

            $entityManager->flush();
            $newStep = $deviceMaintenance->getMaintenanceLogs()[0]->getNextStep();

            if ($newStep) {
                $this->addFlash('success', "L'étape a été avancée avec succès : {$currentStep->getName()} -> {$newStep->getName()}.");
            } else {
                $this->addFlash('success', "Dernière étape déjà atteinte.");
            }
        } catch (\Exception $e) {
            $this->addFlash('error', "Une erreur est survenue : " . $e->getMessage());
        }

        return $this->redirectToRoute('admin_depot');
    }
}
<?php

namespace App\Controller\Admin;

use App\Entity\Client;
use App\Entity\DeviceMaintenance;
use App\Form\DeviceMaintenanceType;
use App\Repository\DeviceMaintenanceRepository;
use App\Service\Client\ClientService;
use App\Service\DeviceMaintenance\DeviceMaintenanceWorkflowService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminEmmausController extends AbstractController
{
    #[Route('/admin/emmaus', name: 'admin_emmaus')]
    #[IsGranted('ROLE_ADMIN_EMMAUS')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(DeviceMaintenance::class);
        $deviceMaintenances = $repository->findBy([], ['deposit' => 'ASC']);

        $devicesByDeposit = [];
        foreach ($deviceMaintenances as $device) {
            if ($device->getMaintenanceLogs()->count() > 0 && $device->getMaintenanceLogs()[0]->getCurrentStep()->getStepOrder() !== 1) {
                $depositName = $device->getDeposit()->getName();
                $devicesByDeposit[$depositName][] = $device;
            }
        }

        return $this->render('admin/emmaus/dashboard.html.twig', [
            'devicesByDeposit' => $devicesByDeposit,
        ]);
    }

    #[Route('/admin/emmaus/maintenance/{id}/next', name: 'maintenance_next_step', methods: ['POST'])]
    public function advanceNextStep(DeviceMaintenance $deviceMaintenance, EntityManagerInterface $entityManager, DeviceMaintenanceWorkflowService $deviceMaintenanceWorkflow): Response
    {
        try {
            // Vérification de l'existence d'un log de maintenance
            $maintenanceLogs = $deviceMaintenance->getMaintenanceLogs();
            if (empty($maintenanceLogs) || empty($maintenanceLogs[0]->getCurrentStep())) {
                $this->addFlash('error', 'Aucun état actuel de maintenance trouvé.');
                return $this->redirectToPreviousPage();
            }

            // Récupération et avancement de l'étape
            $currentStep = $maintenanceLogs[0]->getCurrentStep();
            $stepOrder = $currentStep->getStepOrder();
            $deviceMaintenanceWorkflow->advanceStep($deviceMaintenance, $stepOrder);

            // Sauvegarde en base
            $entityManager->flush();
            $newStep = $deviceMaintenance->getMaintenanceLogs()[0]->getNextStep();

            if($newStep){
                $this->addFlash('success', "L'étape a été avancée avec succès : {$currentStep->getName()} -> {$newStep->getName()}.");
            }else{
                $this->addFlash('success', "Dernière étape déjà atteinte.");
            }
        } catch (\Exception $e) {
            $this->addFlash('error', "Une erreur est survenue : " . $e->getMessage());
        }

        return $this->redirectToPreviousPage();
    }

    #[Route('/admin/emmaus/maintenance/{id}/edit', name: 'maintenance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DeviceMaintenance $deviceMaintenance, EntityManagerInterface $entityManager, ClientService $clientService): Response
    {
        $client = $deviceMaintenance->getClient();
        $form = $this->createForm(DeviceMaintenanceType::class, $deviceMaintenance);

        $form->get('first_name')->setData($client?->getFirstname());
        $form->get('last_name')->setData($client?->getLastname());
        $form->get('email')->setData($client?->getEmail());
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $client = $deviceMaintenance->getClient();
            if ($client) {

                $email = $form->get('email')->getData() ?: $client->getEmail();
                $firstName = $form->get('first_name')->getData() ?: $client->getFirstname();
                $lastName = $form->get('last_name')->getData() ?: $client->getLastname();
    
                $existingClient = $entityManager->getRepository(Client::class)->findOneBy([
                    'email' => $email,
                    'firstname' => $firstName,
                    'lastname' => $lastName,
                ]);
    
                if ($existingClient) {

                    $deviceMaintenance->setClient($existingClient);
                } else {
 
                    $client->setEmail($email);
                    $client->setFirstname($firstName);
                    $client->setLastname($lastName);
                    $entityManager->persist($client);
                }
            }
    
            $entityManager->persist($deviceMaintenance);
            $entityManager->flush();
    
            $this->addFlash('success', 'Maintenance mise à jour avec succès.');
    
            return $this->redirectToRoute('admin_emmaus', ['id' => $deviceMaintenance->getId()]);
        }
    
        return $this->render('admin/emmaus/edit.html.twig', [
            'deviceMaintenance' => $deviceMaintenance,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/emmaus/maintenance/{id}/delete', name: 'maintenance_delete', methods: ['GET'])]
    public function delete(DeviceMaintenance $deviceMaintenance, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($deviceMaintenance);
        $entityManager->flush();

        $this->addFlash('success', 'Maintenance supprimée avec succès.');
        return $this->redirectToRoute('admin_emmaus');
    }

    /**
     * Redirige l'utilisateur en fonction de son rôle.
     */
    private function redirectToPreviousPage(): Response
    {
        if ($this->isGranted('ROLE_ADMIN_POINT_DEPOT')) {
            return $this->redirectToRoute('admin_depot');
        }

        if ($this->isGranted('ROLE_ADMIN_EMMAUS')) {
            return $this->redirectToRoute('admin_emmaus');
        }

        // Redirection par défaut (au cas où)
        return $this->redirectToRoute('homepage');
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\Client;
use App\Entity\Deposit;
use App\Form\DepotType;
use App\Entity\DeviceMaintenance;
use App\Entity\DeviceMaintenanceLog;
use App\Form\DeviceMaintenanceType;
use App\Service\Client\ClientService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\DeviceMaintenanceRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\DeviceMaintenance\DeviceMaintenanceWorkflowService;

class AdminDepotController extends AbstractController
{
    #[Route('/depot-admin', name: 'depot_admin')]
    #[IsGranted('ROLE_ADMIN_POINT_DEPOT')]
    public function depotAdmin(
        Request $request,
        EntityManagerInterface $em,
        ClientService $clientService,
        DeviceMaintenanceWorkflowService $DMWfS
    ): Response {
        $user = $this->getUser();
        $deposit = $em->getRepository(Deposit::class)->find($user->getDeposit());

        $form = $this->createForm(DepotType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $deviceMaintenance = new DeviceMaintenance();
            $deviceMaintenance->setDeposit($deposit);
            $deviceMaintenance->setScreen($data['screen'] ?? 0);
            $deviceMaintenance->setOxidationStatus($data['oxidationStatus'] ?? 0);
            $deviceMaintenance->setHinges($data['hinges'] ?? 0);
            $deviceMaintenance->setFan($data['fan'] ?? 0);
            $deviceMaintenance->setButton($data['button'] ?? 0);
            $deviceMaintenance->setSensors($data['sensors'] ?? 0);
            $deviceMaintenance->setChassis($data['chassis'] ?? 0);
            $deviceMaintenance->setDataWipe($data['dataWipe'] ?? 0);
            $deviceMaintenance->setComputerUnlock($data['computerUnlock'] ?? 0);
            $deviceMaintenance->setDriver($data['driver'] ?? 0);
            $deviceMaintenance->setComputerUpdate($data['computerUpdate'] ?? 0);
            $deviceMaintenance->setMotherboard($data['motherboard'] ?? 0);
            $deviceMaintenance->setNetworks($data['networks'] ?? 0);
            $deviceMaintenance->setComponents($data['components'] ?? 0);
            $deviceMaintenance->setBattery($data['battery'] ?? 0);
            $deviceMaintenance->setPowerSupply($data['powerSupply'] ?? 0);
            // Création client + device
            $client = $clientService->getOrCreateClient(
                $data['email'],
                $data['first_name'],
                $data['last_name']
            );
            $client->addDeviceMaintenance($deviceMaintenance);
            $log = $DMWfS->createInitialLog($deviceMaintenance);
            // dd($client,$deviceMaintenance);

            $em->persist($client);
            $em->flush();

            return $this->redirectToRoute('depot_admin');
        }

        $devices = $em->getRepository(DeviceMaintenance::class)
            ->findBy(['deposit' => $deposit], ['id' => 'DESC']);

        return $this->render('admin/depot/form.html.twig', [
            'form' => $form->createView(),
            'devices' => $devices,
            'deposit' => $deposit
        ]);
    }
    #[Route('/depot-admin-index', name: 'depot_admin_index')]
    #[IsGranted('ROLE_ADMIN_POINT_DEPOT')]
    public function index(
        Request $request,
        DeviceMaintenanceRepository $repository
    ): Response {
        $user = $this->getUSer();
        //todo recuperer tt les device maintenance
        $deviceMaintenance = $repository->findBy(["deposit" => $user->getDeposit()]);

        return $this->render('admin/depot/index.html.twig', [
            'deviceMaintenance' => $deviceMaintenance
        ]);
    }

    #[Route('/maintenance/{id}/edit', name: 'maintenance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DeviceMaintenance $deviceMaintenance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DeviceMaintenanceType::class, $deviceMaintenance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Maintenance mise à jour avec succès.');
            return $this->redirectToRoute('maintenance_edit', ['id' =>$deviceMaintenance->getId()]);
        }

        return $this->render('admin/depot/edit.html.twig', [
            'deviceMaintenance' => $deviceMaintenance,  
            'form' => $form->createView(),
        ]);
    }

    #[Route('/maintenance/{id}/delete', name: 'maintenance_delete', methods: ['GET'])]
    public function delete(DeviceMaintenance $deviceMaintenance, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($deviceMaintenance);
        $entityManager->flush();

        $this->addFlash('success', 'Maintenance supprimée avec succès.');
        return $this->redirectToRoute('depot_admin_index');
    }
}

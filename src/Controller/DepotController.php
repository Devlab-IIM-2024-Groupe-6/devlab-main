<?php

namespace App\Controller;

use App\Entity\Deposit;
use App\Form\DepotType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\DeviceMaintenance;
use App\Service\Client\ClientService;
use App\Service\DeviceMaintenance\DeviceMaintenanceWorkflowService;

class DepotController extends AbstractController
{
    #[Route('/depot', name: 'depot_form_default')]
    public function depotDefault(Request $request, EntityManagerInterface $entityManager, ClientService $clientService, DeviceMaintenanceWorkflowService $DMWfS): Response
    {
        $deposits = $entityManager->getRepository(Deposit::class)->findAll();

        if (empty($deposits)) {
            return $this->render('bundles/TwigBundle/Exception/404.html.twig', [
                'message' => "Aucun dépôt trouvé"
            ], new Response('', 404));
        }

        $defaultDeposit = $deposits[0];

        $form = $this->createForm(DepotType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $deviceMaintenance = new DeviceMaintenance();

            $selectedDepositId = $data['location'] ?? $defaultDeposit->getId();
            $selectedDeposit = $entityManager->getRepository(Deposit::class)->find($selectedDepositId);

            if (!$selectedDeposit) {
                throw $this->createNotFoundException('Dépôt non trouvé');
            }

            $deviceMaintenance->setDeposit($selectedDeposit);
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

            $client = $clientService->getOrCreateClient(
                $data['email'],
                $data['first_name'],
                $data['last_name']
            );
            $client->addDeviceMaintenance($deviceMaintenance);
            $DMWfS->createInitialLog($deviceMaintenance);

            $entityManager->persist($client);
            $entityManager->flush();

            $this->addFlash('success', 'Votre formulaire a été soumis avec succès !');
            return $this->redirectToRoute('depot_form_default');
        }

        return $this->render('depot/form.html.twig', [
            'form' => $form->createView(),
            'deposits' => $deposits,
            'locationName' => $defaultDeposit->getName(),
            'id' => $defaultDeposit->getId(),
            'isDefaultRoute' => true,
        ]);
    }

    #[Route('/depot/{id}', name: 'depot_form', requirements: ['id' => '\d+'])]
    public function depot(Request $request, int $id, EntityManagerInterface $entityManager, ClientService $clientService, DeviceMaintenanceWorkflowService $DMWfS): Response
    {
        $deposit = $entityManager->getRepository(Deposit::class)->find($id);

        if (!$deposit) {
            return $this->render('bundles/TwigBundle/Exception/404.html.twig', [
                'message' => "Aucun dépôt trouvé"
            ], new Response('', 404));
        }

        $form = $this->createForm(DepotType::class, null, [
            'hide_location' => true,
        ]);
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

            $client = $clientService->getOrCreateClient(
                $data['email'],
                $data['first_name'],
                $data['last_name']
            );
            $client->addDeviceMaintenance($deviceMaintenance);
            $DMWfS->createInitialLog($deviceMaintenance);

            $entityManager->persist($client);
            $entityManager->flush();

            $this->addFlash('success', 'Votre formulaire a été soumis avec succès !');
            return $this->redirectToRoute('depot_form', ['id' => $id]);
        }

        return $this->render('depot/form.html.twig', [
            'form' => $form->createView(),
            'locationName' => $deposit->getName(),
            'id' => $id,
            'isDefaultRoute' => false,
        ]);
    }

    #[Route('/api/locations', name: 'api_locations', methods: ['GET'])]
    public function getLocations(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $searchQuery = $request->query->get('search');
        $deposits = $entityManager->getRepository(Deposit::class)->findAll();

        if ($searchQuery) {
            $deposits = array_filter($deposits, function ($deposit) use ($searchQuery) {
                return stripos($deposit->getName(), $searchQuery) !== false ||
                    stripos($deposit->getAddress(), $searchQuery) !== false;
            });
        }

        $locations = [];
        foreach ($deposits as $deposit) {
            $locations[] = [
                'id' => $deposit->getId(),
                'latitude' => $deposit->getLatitude(),
                'longitude' => $deposit->getLongitude(),
                'title' => $deposit->getName(),
            ];
        }

        return new JsonResponse($locations);
    }

    #[Route('/map/{id}', name: 'map', methods: ['GET'])]
    public function map(int $id): Response
    {
        return $this->render('map.html.twig', [
            'id' => $id,
        ]);
    }
}

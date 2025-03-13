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
use App\Entity\Client;
use App\Entity\DeviceMaintenance;
use App\Service\Client\ClientService;
use App\Service\DeviceMaintenance\DeviceMaintenanceWorkflowService;

class DepotController extends AbstractController
{
    #[Route('/depot/{id}', name: 'depot_form')]
    public function depot(Request $request, int $id, EntityManagerInterface $entityManager, ClientService $clientService, DeviceMaintenanceWorkflowService $DMWfS): Response
    {
        $deposit = $entityManager->getRepository(Deposit::class)->find($id);

        if (!$deposit) {
            return $this->render('bundles/TwigBundle/Exception/404.html.twig', [
                'message' => "Localisation non trouvée"
            ], new Response('', 404));
        }

        $form = $this->createForm(DepotType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            // Créer un objet DeviceMaintenance
            $deviceMaintenance = new DeviceMaintenance();

            // Assigner les autres données du formulaire à DeviceMaintenance
            // $deviceMaintenance->setCurrentStep($data['currentStep'] ?? 0);
            // dd($data);
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
            $log = $DMWfS->createInitialLog($deviceMaintenance);
            // dd($client,$deviceMaintenance);
            $entityManager->persist($client);
            $entityManager->flush();
        }

        return $this->render('depot/form.html.twig', [
            'form' => $form->createView(),
            'locationName' => $deposit->getName(),
            'id' => $id,
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

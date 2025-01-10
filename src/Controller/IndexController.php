<?php

// src/Controller/IndexController.php
namespace App\Controller;

use App\Entity\Client;
use App\Service\BarcodeGeneratorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    private BarcodeGeneratorService $barcodeGenerator;

    public function __construct(BarcodeGeneratorService $barcodeGenerator)
    {
        $this->barcodeGenerator = $barcodeGenerator;
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('index/index.html.twig');
    }

    #[Route('/suivi', name: 'tracking', methods: ['GET', 'POST'])]
    public function tracking(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $trackingNumber = $request->request->get('tracking_number');
            
            $client = $entityManager->getRepository(Client::class)->findOneBy(['trackingNumber' => $trackingNumber]);
    
            if ($client) {
                return $this->redirectToRoute('trackingId', ['trackingNumber' => $trackingNumber]);
            }

            $this->addFlash('error', 'Numéro de suivi invalide ou introuvable.');
        }
    
        return $this->render('tracking.html.twig', [
            'title' => 'Suivi de mon dépôt'
        ]);
    }

    #[Route('/suivi/{trackingNumber}', name: 'trackingId', methods: ['GET', 'POST'])]
    public function trackingId(string $trackingNumber, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $trackingNumber = $request->request->get('tracking_number');
            
            $client = $entityManager->getRepository(Client::class)->findOneBy(['trackingNumber' => $trackingNumber]);
    
            if ($client) {
                return $this->redirectToRoute('trackingId', ['trackingNumber' => $trackingNumber]);
            }

            $this->addFlash('error', 'Numéro de suivi invalide ou introuvable.');
            return $this->render('tracking.html.twig', [
                'title' => 'Suivi de mon dépôt',
                'hasResult' => false
            ]);
        }

        $client = $entityManager->getRepository(Client::class)->findOneBy(['trackingNumber' => $trackingNumber]);

        if (!$client) {
            throw $this->createNotFoundException("Client ou utilisateur introuvable.");
        }

        $deposit = $client->getDeposit();

        return $this->render('tracking.html.twig', [
            'title' => 'Suivi de mon dépôt',
            'hasResult' => true,
            'trackingNumber' => $trackingNumber,
            'deposit' => $deposit,
            'client' => $client
        ]);
    }

    #[Route('/wiki', name: 'wiki')]
    public function wiki(): Response
    {
        return $this->render('wiki.html.twig', [
            'title' => 'Wiki des problèmes récurrents'
        ]);
    }

    #[Route('/barcode/{trackingNumber}', name: 'client_barcode')]
    public function generateClientBarcode(string $trackingNumber, EntityManagerInterface $entityManager): Response
    {
        $client = $entityManager->getRepository(Client::class)->findOneBy(['trackingNumber' => $trackingNumber]);

        if (!$client) {
            throw $this->createNotFoundException("Client introuvable.");
        }

        if (!$trackingNumber) {
            throw $this->createNotFoundException("Le client n'a pas de numéro de suivi.");
        }
    
        $barcode = $this->barcodeGenerator->generateBarcode($trackingNumber);
    
        $deposit = $client->getDeposit();

        return $this->render('barcode/barcode.html.twig', [
            'client' => $client,
            'barcode' => $barcode,
            'trackingNumber' => $trackingNumber,
            'deposit' => $deposit
        ]);
    }
}

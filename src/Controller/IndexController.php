<?php

// src/Controller/IndexController.php
namespace App\Controller;

use App\Entity\Client;
use App\Service\BarcodeGeneratorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    #[Route('/suivi', name: 'tracking')]
    public function tracking(): Response
    {
        return $this->render('tracking.html.twig', [
            'title' => 'Suivi de mon dépôt'
        ]);
    }

    #[Route('/wiki', name: 'wiki')]
    public function wiki(): Response
    {
        return $this->render('wiki.html.twig', [
            'title' => 'Wiki des problèmes récurrents'
        ]);
    }

    #[Route('/client/{trackingNumber}', name: 'client_barcode')]
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

        return $this->render('client/barcode.html.twig', [
            'client' => $client,
            'barcode' => $barcode,
            'trackingNumber' => $trackingNumber,
            'deposit' => $deposit
        ]);
    }    
}

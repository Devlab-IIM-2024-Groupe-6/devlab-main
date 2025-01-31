<?php

// src/Controller/IndexController.php
namespace App\Controller;

use App\Entity\Client;
use App\Entity\DeviceMaintenance;
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
            'title' => 'Suivi de mon dépôt',
            'hasResult' => false
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
            return $this->render('bundles/TwigBundle/Exception/404.html.twig', [
                'message' => "Client ou utilisateur introuvable."
            ], new Response('', 404));
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
            return $this->render('bundles/TwigBundle/Exception/404.html.twig', [
                'message' => 'Client introuvable'
            ], new Response('', 404));
        }

        if (!$trackingNumber) {
            return $this->render('bundles/TwigBundle/Exception/404.html.twig', [
                'message' => "Le client n'a pas de numéro de suivi."
            ], new Response('', 404));
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

    #[Route('/certificate/{trackingNumber}', name: 'client_certificate')]
    public function generateCertificate(string $trackingNumber, EntityManagerInterface $entityManager): Response
    {
        $client = $entityManager->getRepository(Client::class)->findOneBy(['trackingNumber' => $trackingNumber]);
    
        if (!$client) {
            return $this->render('bundles/TwigBundle/Exception/404.html.twig', [
                'message' => 'Client introuvable'
            ], new Response('', 404));
        }
    
        $deviceMaintenance = $entityManager->getRepository(DeviceMaintenance::class)->findOneBy(['trackingNumber' => $client]);
    
        return $this->render('certificate/certificate.html.twig', [
            'client' => $client,
            'deviceMaintenance' => $deviceMaintenance,
            'trackingNumber' => $trackingNumber,
        ]);
    }

    #[Route('/download/{type}/{trackingNumber}', name: 'download_pdf')]
    public function downloadPdf(string $type, string $trackingNumber, EntityManagerInterface $entityManager): Response
    {
        $client = $entityManager->getRepository(Client::class)->findOneBy(['trackingNumber' => $trackingNumber]);

        if (!$client) {
            return $this->render('bundles/TwigBundle/Exception/404.html.twig', [
                'message' => 'Client introuvable'
            ], new Response('', 404));
        }

        if ($type === 'barcode') {
            $html = $this->renderView('barcode/barcode_pdf.html.twig', [
                'client' => $client,
                'trackingNumber' => $trackingNumber,
                'barcode' => $this->barcodeGenerator->generateBarcode($trackingNumber),
                'deposit' => $client->getDeposit(),
            ]);
        } elseif ($type === 'certificate') {
            $deviceMaintenance = $entityManager->getRepository(DeviceMaintenance::class)->findOneBy(['trackingNumber' => $client]);

            $html = $this->renderView('certificate/certificate_pdf.html.twig', [
                'client' => $client,
                'deviceMaintenance' => $deviceMaintenance,
                'trackingNumber' => $trackingNumber,
            ]);
        } else {
            return $this->render('bundles/TwigBundle/Exception/404.html.twig', [
                'message' => 'Type de téléchargement non supporté'
            ], new Response('', 404));
        }

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $response = new Response($dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $type . '_' . $trackingNumber . '.pdf"');

        return $response;
    }

    #[Route('/{any}', name: 'not_found', requirements: ['any' => '.*'])]
    public function notFound(): Response
    {
        return $this->render('bundles/TwigBundle/Exception/404.html.twig', [], new Response('', 404));
    }
}

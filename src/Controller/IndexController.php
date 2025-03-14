<?php

// src/Controller/IndexController.php
namespace App\Controller;

use App\Entity\DeviceMaintenance;
use App\Form\TrackingFormType;
use App\Service\BarcodeGeneratorService;
use App\Service\DeviceMaintenance\TrackingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('index/index.html.twig');
    }

    #[Route('/suivi/{trackingNumber}', name: 'tracking', methods: ['GET', 'POST'], defaults: ['trackingNumber' => null])]
    public function tracking(?string $trackingNumber, Request $request, EntityManagerInterface $em, TrackingService $trackingService): Response
    {
        $form = $this->createForm(TrackingFormType::class);
        $form->handleRequest($request);
    
        // Gestion du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $trackingNumber = $form->getData()['tracking_number'];
            
            if ($trackingService->validateTrackingNumber($trackingNumber)) {
                return $this->redirectToRoute('tracking', ['trackingNumber' => $trackingNumber]);
            }
    
            $this->addFlash('error', 'Numéro de suivi invalide ou introuvable.');
            return $this->redirectToRoute('tracking');
        }
    
        // Gestion de l'affichage des résultats
        $result = $trackingNumber ? $trackingService->getTrackingResult($trackingNumber) : null;

        if ($trackingNumber && !$result) {
            return $this->render('bundles/TwigBundle/Exception/404.html.twig', [
                'message' => 'Client ou utilisateur introuvable.'
            ], new Response('', 404));
        }

        return $this->render('tracking.html.twig', [
            'form' => $form->createView(),
            'result' => $result,
            'trackingNumber' => $trackingNumber
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
        $deviceMaintenance = $entityManager->getRepository(DeviceMaintenance::class)->findOneBy(['trackingNumber' => $trackingNumber]);
        if (!$deviceMaintenance) {
            return $this->render('bundles/TwigBundle/Exception/404.html.twig', [
                'message' => 'Numéro de tracking introuvable'
            ], new Response('', 404));
        }

        $client = $deviceMaintenance->getClient();

        if (!$trackingNumber) {
            return $this->render('bundles/TwigBundle/Exception/404.html.twig', [
                'message' => "Le client n'a pas de numéro de suivi."
            ], new Response('', 404));
        }

        $barcode = $this->barcodeGenerator->generateBarcode($trackingNumber);

        $deposit = $deviceMaintenance->getDeposit();

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
        $deviceMaintenance = $entityManager->getRepository(DeviceMaintenance::class)->findOneBy(['trackingNumber' => $trackingNumber]);
        if (!$deviceMaintenance) {
            return $this->render('bundles/TwigBundle/Exception/404.html.twig', [
                'message' => 'Client introuvable'
            ], new Response('', 404));
        }
        
        $client = $deviceMaintenance->getClient();
        $latestMaintenanceLog = $deviceMaintenance->getLatestMaintenanceLog();
        $currentStep = $latestMaintenanceLog ? $latestMaintenanceLog->getCurrentStep() : null;

        return $this->render('certificate/certificate.html.twig', [
            'client' => $client,
            'deviceMaintenance' => $deviceMaintenance,
            'trackingNumber' => $trackingNumber,
            'currentStep' => $currentStep,
        ]);
    }

    #[Route('/download/{type}/{trackingNumber}', name: 'download_pdf')]
    public function downloadPdf(string $type, string $trackingNumber, EntityManagerInterface $entityManager): Response
    {
        $deviceMaintenance = $entityManager->getRepository(DeviceMaintenance::class)->findOneBy(['trackingNumber' => $trackingNumber]);
        if (!$deviceMaintenance) {
            return $this->render('bundles/TwigBundle/Exception/404.html.twig', [
                'message' => 'Client introuvable'
            ], new Response('', 404));
        }

        $client = $deviceMaintenance->getClient();

        if ($type === 'barcode') {
            $html = $this->renderView('barcode/barcode_pdf.html.twig', [
                'client' => $client,
                'trackingNumber' => $trackingNumber,
                'barcode' => $this->barcodeGenerator->generateBarcode($trackingNumber),
                'deposit' => $deviceMaintenance->getDeposit(),
            ]);
        } elseif ($type === 'certificate') {
            $latestMaintenanceLog = $deviceMaintenance->getLatestMaintenanceLog();
            $currentStep = $latestMaintenanceLog ? $latestMaintenanceLog->getCurrentStep() : null;
            $html = $this->renderView('certificate/certificate_pdf.html.twig', [
                'client' => $client,
                'deviceMaintenance' => $deviceMaintenance,
                'trackingNumber' => $trackingNumber,
                'currentStep' => $currentStep,
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

    #[Route(
        '/{any}',
        name: 'not_found',
        requirements: [
            'any' => '^(?!.*(login|logout|admin)).*$'
        ]
    )]
    public function notFound(): Response
    {
        return $this->render('bundles/TwigBundle/Exception/404.html.twig', [], new Response('', 404));
    }
}

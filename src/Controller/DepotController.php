<?php

namespace App\Controller;

use App\Entity\Deposit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class DepotController extends AbstractController
{
    #[Route('/depot', name: 'depot_form')]
    public function depot(Request $request): Response
    {
        $form = $this->createForm(DepotType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->addFlash('success', 'Votre dépôt a été enregistré avec succès.');
            return $this->redirectToRoute('index');
        }

        return $this->render('depot/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/api/locations', name: 'api_locations', methods: ['GET'])]
    public function getLocations(EntityManagerInterface $entityManager): JsonResponse
    {
        $deposits = $entityManager->getRepository(Deposit::class)->findAll();

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

<?php

namespace App\Controller\Admin;

use App\Entity\DeviceMaintenance;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminEmmausController extends AbstractController
{

    #[Route('/admin/emmaus/maintenance', name: 'admin_emmaus_maintenance')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(DeviceMaintenance::class);

        // Récupération des appareils classés par dépôt
        $deviceMaintenances = $repository->findBy([], ['deposit' => 'ASC']);

        // Grouper les devices par dépôt
        $devicesByDeposit = [];
        foreach ($deviceMaintenances as $device) {
            $depositName = $device->getDeposit()->getName();
            $devicesByDeposit[$depositName][] = $device;
        }

        return $this->render('admin/emmaus/_maintenance.html.twig', [
            'devicesByDeposit' => $devicesByDeposit,
        ]);
    }
}

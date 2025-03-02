<?php

namespace App\Controller\Admin;

use App\Entity\Client;
use App\Entity\DeviceMaintenance;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN_POINT_DEPOT')]
class AdminPointDepotController extends AbstractDashboardController
{
    #[Route('/admin-point-depot', name: 'admin_point_depot')]
    public function index(): Response
    {
        $user = $this->getUser();

        if (!$user || !$user->getDeposit()) {
            throw $this->createAccessDeniedException("Vous devez être affilié à un point de dépôt.");
        }

        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(ClientCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Administration Point Dépôt');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Clients', 'fa fa-user', Client::class);
        yield MenuItem::linkToCrud('Maintenance', 'fa fa-tools', DeviceMaintenance::class);
    }
}

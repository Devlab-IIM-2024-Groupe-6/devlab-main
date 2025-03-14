<?php

namespace App\Controller\Admin;

use App\Entity\Client;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ClientCrudController extends AbstractCrudController
{
    private EntityManagerInterface $entityManager;
    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        // $this->security = $security;
        $this->entityManager = $entityManager;
    }

    public static function getEntityFqcn(): string
    {
        return Client::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm()->hideOnIndex(),
            TextField::new('email'),
            TextField::new('firstname'),
            TextField::new('lastname'),
        ];
    }

    // public function createIndexQueryBuilder(
    //     SearchDto $searchDto,
    //     EntityDto $entityDto,
    //     FieldCollection $fields,
    //     FilterCollection $filters
    // ): QueryBuilder {
    //     /** @var \App\Entity\User $user */
    //     $user = $this->getUser();

    //     if (!$user || !$user->getDeposit()) {
    //         throw new AccessDeniedException("Vous n'êtes pas affilié à un point de dépôt.");
    //     }

    //     // Filtrer les clients pour ne montrer que ceux du dépôt de l'utilisateur connecté
    //     $queryBuilder = $this->entityManager->getRepository(Client::class)
    //         ->createQueryBuilder('c')
    //         ->where('c.deposit = :deposit')
    //         ->setParameter('deposit', $user->getDeposit());

    //     return $queryBuilder;
    // }
}

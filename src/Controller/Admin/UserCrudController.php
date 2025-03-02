<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        $roleChoices = [
            'User' => 'ROLE_USER',
            'Deposit' => 'ROLE_DEPOSIT',
            'Admin' => 'ROLE_ADMIN',
            'Admin Point Dépôt' => 'ROLE_ADMIN_POINT_DEPOT',
            'Admin Emmaüs' => 'ROLE_ADMIN_EMMAUS',
        ];

        $rolesField = ChoiceField::new('roles')
            ->setChoices($roleChoices)
            ->allowMultipleChoices(true);

        $rolesField->setFormTypeOption('data', ['ROLE_USER']);

        return [
            IdField::new('id')->hideOnForm()->hideOnIndex(),
            AssociationField::new('deposit')
                ->setRequired(true)
                ->setFormTypeOption('attr', ['required' => 'required']),
            TextField::new('firstname'),
            TextField::new('lastname'),
            TextField::new('Email')->setRequired(true),
            TextField::new('password'),
            $rolesField,
            TextField::new('tracking_number')->hideOnForm(),
        ];
    }

    public function persistEntity(\Doctrine\ORM\EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof User) {
            $hashedPassword = $this->passwordHasher->hashPassword($entityInstance, $entityInstance->getPassword());
            $entityInstance->setPassword($hashedPassword);
        }

        parent::persistEntity($entityManager, $entityInstance);
    }
}

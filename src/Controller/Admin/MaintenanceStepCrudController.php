<?php
// src/Controller/Admin/MaintenanceStepCrudController.php

namespace App\Controller\Admin;

use App\Entity\MaintenanceStep;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class MaintenanceStepCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MaintenanceStep::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Étape de maintenance')
            ->setEntityLabelInPlural('Étapes de maintenance')
            ->setSearchFields(['name', 'description'])
            ->setDefaultSort(['stepOrder' => 'ASC'])
            ->setPaginatorPageSize(20);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm()->hideOnIndex(),

            TextField::new('name', 'Nom')
                ->setRequired(true)
                ->setHelp('Nom de l\'étape (ex: "Diagnostic", "Réparation")'),

            TextareaField::new('description', 'Description')
                ->setRequired(false)
                ->hideOnIndex()
                ->setHelp('Description détaillée de l\'étape'),

            IntegerField::new('stepOrder', 'Ordre d\'affichage')
                ->setRequired(true)
                ->setHelp('Position dans le déroulement des étapes (plus petit = première position)')
        ];
    }
}

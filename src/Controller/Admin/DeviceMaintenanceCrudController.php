<?php

namespace App\Controller\Admin;

use App\Entity\DeviceMaintenance;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class DeviceMaintenanceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DeviceMaintenance::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm()->hideOnIndex(),
            AssociationField::new('trackingNumber')
                ->setFormTypeOption('query_builder', function($repository) {
                    return $repository->createQueryBuilder('c')
                        ->orderBy('c.trackingNumber', 'ASC');
                })
                ->setFormTypeOption('choice_label', 'trackingNumber'),
            ChoiceField::new('currentStep')
                ->setChoices([
                    1 => '1',
                    2 => '2',
                    3 => '3',
                    4 => '4',
                    5 => '5',
                    6 => '6',
                ])
                ->setFormTypeOption('attr', [
                    'class' => 'form-control',
                ]),
            BooleanField::new('screen'),
            BooleanField::new('oxidationStatus'),
            BooleanField::new('hinges'),
            BooleanField::new('fan'),
            BooleanField::new('button'),
            BooleanField::new('sensors'),
            BooleanField::new('chassis'),
            BooleanField::new('dataWipe'),
            BooleanField::new('computerUnlock'),
            BooleanField::new('driver'),
            BooleanField::new('computerUpdate'),
            BooleanField::new('motherboard'),
            BooleanField::new('networks'),
            BooleanField::new('components'),
            BooleanField::new('battery'),
            BooleanField::new('powerSupply'),
        ];
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\DeviceMaintenance;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
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
            AssociationField::new('user')
                ->setRequired(true)
                ->setFormTypeOption('attr', ['required' => 'required']),
            TextField::new('trackingNumber'),
            IntegerField::new('currentStep')
                ->setFormTypeOption('attr', [
                    'min' => 1,
                    'max' => 6,
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

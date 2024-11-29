<?php

namespace App\Controller\Admin;

use App\Entity\Deposit;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class DepositCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Deposit::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm()->hideOnIndex(),
            TextField::new('name'),
            TextField::new('address'),
            NumberField::new('latitude'),
            NumberField::new('longitude'),
            TextField::new('schedules'),
            TextEditorField::new('description'),
        ];
    }
}

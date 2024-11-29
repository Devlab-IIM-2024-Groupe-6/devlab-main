<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class DepotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('item_name', TextType::class, [
                'label' => 'Nom du matériel',
                'attr' => ['placeholder' => 'Ex : Ordinateur portable']
            ])
            ->add('item_description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['placeholder' => 'Décrivez l’état du matériel']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Soumettre',
                'attr' => ['class' => 'btn btn-success']
            ]);
    }
}

<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
class TrackingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tracking_number', TextType::class, [
                'label' => 'Numéro de suivi',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Ex : 12345-ABCDE'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Vérifier',
                'attr' => ['class' => 'btn btn-primary w-full']
            ]);
    }
}
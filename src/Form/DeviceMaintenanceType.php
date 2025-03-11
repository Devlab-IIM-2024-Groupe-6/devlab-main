<?php
namespace App\Form;

use App\Entity\DeviceMaintenance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeviceMaintenanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('trackingNumber', TextType::class, [
                'required' => false,
                'label' => 'Numéro de suivi'
            ])
            ->add('screen', CheckboxType::class, [
                'required' => false,
                'label' => 'Écran'
            ])
            ->add('oxidationStatus', CheckboxType::class, [
                'required' => false,
                'label' => 'Oxydation'
            ])
            ->add('hinges', CheckboxType::class, [
                'required' => false,
                'label' => 'Charnières'
            ])
            ->add('fan', CheckboxType::class, [
                'required' => false,
                'label' => 'Ventilateur'
            ])
            ->add('button', CheckboxType::class, [
                'required' => false,
                'label' => 'Boutons'
            ])
            ->add('sensors', CheckboxType::class, [
                'required' => false,
                'label' => 'Capteurs'
            ])
            ->add('chassis', CheckboxType::class, [
                'required' => false,
                'label' => 'Châssis'
            ])
            ->add('dataWipe', CheckboxType::class, [
                'required' => false,
                'label' => 'Effacement des données'
            ])
            ->add('computerUnlock', CheckboxType::class, [
                'required' => false,
                'label' => 'Déverrouillage ordinateur'
            ])
            ->add('driver', CheckboxType::class, [
                'required' => false,
                'label' => 'Pilotes'
            ])
            ->add('computerUpdate', CheckboxType::class, [
                'required' => false,
                'label' => 'Mise à jour ordinateur'
            ])
            ->add('motherboard', CheckboxType::class, [
                'required' => false,
                'label' => 'Carte mère'
            ])
            ->add('networks', CheckboxType::class, [
                'required' => false,
                'label' => 'Réseaux'
            ])
            ->add('components', CheckboxType::class, [
                'required' => false,
                'label' => 'Composants'
            ])
            ->add('battery', CheckboxType::class, [
                'required' => false,
                'label' => 'Batterie'
            ])
            ->add('powerSupply', CheckboxType::class, [
                'required' => false,
                'label' => 'Alimentation'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DeviceMaintenance::class,
        ]);
    }
}

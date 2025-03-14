<?php

namespace App\Form;

use App\Entity\Deposit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('first_name', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['placeholder' => 'Ex : Jean']
            ])
            ->add('last_name', TextType::class, [
                'label' => 'Nom',
                'attr' => ['placeholder' => 'Ex : Dupont']
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['placeholder' => 'Ex : jean.dupont@email.com']
            ]);
        if (!$options['hide_location']) {
            $builder->add('location', EntityType::class, [
                'label' => 'Localisation',
                'class' => Deposit::class,
                'choice_label' => 'name',
                'placeholder' => 'Sélectionnez une localisation',
                'required' => true,
            ]);
        }
        $builder
            ->add('screen', CheckboxType::class, [
                'label' => 'Écran',
                'required' => false
            ])
            ->add('oxidationStatus', CheckboxType::class, [
                'label' => 'État d\'oxydation',
                'required' => false
            ])
            ->add('hinges', CheckboxType::class, [
                'label' => 'Charnières',
                'required' => false
            ])
            ->add('fan', CheckboxType::class, [
                'label' => 'Ventilateur',
                'required' => false
            ])
            ->add('button', CheckboxType::class, [
                'label' => 'Boutons',
                'required' => false
            ])
            ->add('sensors', CheckboxType::class, [
                'label' => 'Capteurs',
                'required' => false
            ])
            ->add('chassis', CheckboxType::class, [
                'label' => 'Châssis',
                'required' => false
            ])
            ->add('dataWipe', CheckboxType::class, [
                'label' => 'Effacement des données',
                'required' => false
            ])
            ->add('computerUnlock', CheckboxType::class, [
                'label' => 'Déverrouillage PC',
                'required' => false
            ])
            ->add('driver', CheckboxType::class, [
                'label' => 'Pilotes',
                'required' => false
            ])
            ->add('computerUpdate', CheckboxType::class, [
                'label' => 'Mise à jour PC',
                'required' => false
            ])
            ->add('motherboard', CheckboxType::class, [
                'label' => 'Carte mère',
                'required' => false
            ])
            ->add('networks', CheckboxType::class, [
                'label' => 'Réseaux',
                'required' => false
            ])
            ->add('components', CheckboxType::class, [
                'label' => 'Composants',
                'required' => false
            ])
            ->add('battery', CheckboxType::class, [
                'label' => 'Batterie',
                'required' => false
            ])
            ->add('powerSupply', CheckboxType::class, [
                'label' => 'Alimentation',
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Soumettre',
                'attr' => ['class' => 'btn btn-success']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'hide_location' => false,
        ]);
    }
}

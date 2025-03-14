<?php

namespace App\Controller\Admin;

use App\Entity\Client;
use App\Entity\DeviceMaintenance;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class DeviceMaintenanceCrudController extends AbstractCrudController
{
    private Security $security;
    private EntityManagerInterface $entityManager;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    public static function getEntityFqcn(): string
    {
        return DeviceMaintenance::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm()->hideOnIndex(),

            // Tracking Number (clé de liaison avec Client)
            TextField::new('trackingNumber')->hideOnForm()
                ->setHelp("Identifiant unique pour lier le client à la maintenance."),

            FormField::addPanel('Informations Client')
                ->setHelp("Ces champs ne sont pas enregistrés en base mais utilisés pour la maintenance."),

            // Champs non mappés pour afficher/saisir les infos du client
            AssociationField::new('client', 'Client associé')
            ->setRequired(true)
            ->autocomplete()
            ->formatValue(function ($value, $entity) {
                return $entity->getClient()?->getEmail();
            }),

            FormField::addPanel('Détails de la Maintenance'),
            
            // ChoiceField::new('currentStep')
            //     ->setChoices([
            //         1 => '1',
            //         2 => '2',
            //         3 => '3',
            //         4 => '4',
            //         5 => '5',
            //         6 => '6',
            //     ]),

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

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof DeviceMaintenance) {
            return;
        }

        // Vérification de l'utilisateur et de son dépôt
        $user = $this->security->getUser();
        if (!$user || !$user->getDeposit()) {
            throw new \RuntimeException("Vous devez être affilié à un point de dépôt.");
        }

        // Génération automatique du trackingNumber si inexistant
        if (!$entityInstance->getTrackingNumber()) {
            $entityInstance->generateTrackingNumber();
        }

        // Vérifier si un client avec ce trackingNumber existe déjà
        $client = $this->entityManager->getRepository(Client::class)
            ->findOneBy(['trackingNumber' => $entityInstance->getTrackingNumber()]);

        if (!$client) {
            // On ne crée pas en base mais on peut simuler une liaison temporaire
            $client = new Client();
            $client->setEmail('Non enregistré');
            $client->setFirstname('Non enregistré');
            $client->setLastname('Non enregistré');
        }

        // Mettre à jour les champs non mappés
        $entityInstance->clientEmail = $client->getEmail();
        $entityInstance->clientFirstname = $client->getFirstname();
        $entityInstance->clientLastname = $client->getLastname();

        // Persiste la maintenance avec le trackingNumber associé
        parent::persistEntity($entityManager, $entityInstance);
    }
}

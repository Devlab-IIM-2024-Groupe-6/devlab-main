<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Deposit;
use App\Entity\DeviceMaintenance;
use App\Entity\DeviceMaintenanceLog;
use App\Entity\MaintenanceStep;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        // 1) Création du dépôt
        $deposit = new Deposit();
        $deposit->setName('Curious Lab');
        $deposit->setAddress('Curious Lab Address');
        $deposit->setLatitude(48.8931777);
        $deposit->setLongitude(2.226908);
        $deposit->setSchedules('Curious Lab Schedules');
        $deposit->setDescription('Curious Lab Description');
        $manager->persist($deposit);

        $testDeposit = new Deposit();
        $testDeposit->setName('Curious Lab Test');
        $testDeposit->setAddress('Curious Lab Address Test');
        $testDeposit->setLatitude(48.6931733);
        $testDeposit->setLongitude(2.326998);
        $testDeposit->setSchedules('Curious Lab Schedules Test');
        $testDeposit->setDescription('Curious Lab Description Test');
        $manager->persist($testDeposit);

        // 2) Création d'un admin, rattaché au Deposit
        $userSuperAdmin = new User();
        $userSuperAdmin->setEmail('curiouslab@admin.com');
        $hashedPassword = $this->userPasswordHasher->hashPassword($userSuperAdmin, 'aMLBkroE6gJmS&$$');
        $userSuperAdmin->setPassword($hashedPassword);
        $userSuperAdmin->setRoles(['ROLE_ADMIN']);
        $userSuperAdmin->setFirstname('Curious');
        $userSuperAdmin->setLastname('Lab');
        $userSuperAdmin->setDeposit($deposit);
        $manager->persist($userSuperAdmin);

        $userAdminPointDepot = new User();
        $userAdminPointDepot->setEmail('depot@admin.com');
        $hashedPassword = $this->userPasswordHasher->hashPassword($userAdminPointDepot, 'aMLBkroE6gJmS&$$');
        $userAdminPointDepot->setPassword($hashedPassword);
        $userAdminPointDepot->setRoles(['ROLE_ADMIN_POINT_DEPOT']);
        $userAdminPointDepot->setFirstname('Depot');
        $userAdminPointDepot->setLastname('Admin');
        $userAdminPointDepot->setDeposit($testDeposit);
        $manager->persist($userAdminPointDepot);

        $userAdminEmmaus = new User();
        $userAdminEmmaus->setEmail('emmaus@admin.com');
        $hashedPassword = $this->userPasswordHasher->hashPassword($userAdminEmmaus, 'aMLBkroE6gJmS&$$');
        $userAdminEmmaus->setPassword($hashedPassword);
        $userAdminEmmaus->setRoles(['ROLE_ADMIN_EMMAUS']);
        $userAdminEmmaus->setFirstname('Emmaus');
        $userAdminEmmaus->setLastname('Admin');
        $manager->persist($userAdminEmmaus);

        // 3) Création d’un Client
        $client = new Client();
        $client->setEmail('john@doe.com');
        $client->setFirstname('John');
        $client->setLastname('Doe');
        $manager->persist($client);

        // 4) Création des 5 étapes de maintenance, avec l'ordre
        $step1 = new MaintenanceStep();
        $step1->setName('Dépôt du formulaire');
        $step1->setStepOrder(1);
        $manager->persist($step1);

        $step2 = new MaintenanceStep();
        $step2->setName('Envoi vers Emmaüs');
        $step2->setStepOrder(2);
        $manager->persist($step2);

        $step3 = new MaintenanceStep();
        $step3->setName('Réception par Emmaüs');
        $step3->setStepOrder(3);
        $manager->persist($step3);

        $step4 = new MaintenanceStep();
        $step4->setName('Remise à zéro du matériel');
        $step4->setStepOrder(4);
        $manager->persist($step4);

        $step5 = new MaintenanceStep();
        $step5->setName('Envoi final au client');
        $step5->setStepOrder(5);
        $manager->persist($step5);

        // 5) Création d’un DeviceMaintenance lié au Client et au Deposit
        $deviceMaintenance = new DeviceMaintenance();
        $deviceMaintenance->setClient($client);
        $deviceMaintenance->setDeposit($deposit);
        // Quelques exemples pour les champs booléens
        $deviceMaintenance->setScreen(true);
        $deviceMaintenance->setFan(true);
        $deviceMaintenance->setButton(false);
        $manager->persist($deviceMaintenance);

        $deviceMaintenance2 = new DeviceMaintenance();
        $deviceMaintenance2->setClient($client);
        $deviceMaintenance2->setDeposit($deposit);
        $manager->persist($deviceMaintenance2);

        $deviceMaintenance3 = new DeviceMaintenance();
        $deviceMaintenance3->setClient($client);
        $deviceMaintenance3->setDeposit($deposit);
        $manager->persist($deviceMaintenance3);

        $deviceMaintenance4 = new DeviceMaintenance();
        $deviceMaintenance4->setClient($client);
        $deviceMaintenance4->setDeposit($deposit);
        $manager->persist($deviceMaintenance4);

        $deviceMaintenance5 = new DeviceMaintenance();
        $deviceMaintenance5->setClient($client);
        $deviceMaintenance5->setDeposit($deposit);
        $manager->persist($deviceMaintenance5);

        // 6) Création des logs de maintenance (un par étape)
        $log1 = new DeviceMaintenanceLog();
        $log1->setDeviceMaintenance($deviceMaintenance);
        $log1->setNextStep($step2);
        $log1->setCurrentStep($step1);   
        $log1->setChangedAt(new \DateTime());
        $manager->persist($log1);

        $log2 = new DeviceMaintenanceLog();
        $log2->setDeviceMaintenance($deviceMaintenance2);
        $log2->setPreviousStep($step1);
        $log2->setNextStep($step3);
        $log2->setCurrentStep($step2);
        $log2->setChangedAt(new \DateTime());
        $manager->persist($log2);

        $log3 = new DeviceMaintenanceLog();
        $log3->setDeviceMaintenance($deviceMaintenance3);
        $log3->setCurrentStep($step3);
        $log3->setPreviousStep($step2);
        $log3->setNextStep($step4);
        $log3->setChangedAt(new \DateTime());
        $manager->persist($log3);

        $log4 = new DeviceMaintenanceLog();
        $log4->setDeviceMaintenance($deviceMaintenance4);
        $log4->setCurrentStep($step4);
        $log4->setPreviousStep($step3);
        $log4->setNextStep($step5);
        $log4->setChangedAt(new \DateTime());
        $manager->persist($log4);

        $log5 = new DeviceMaintenanceLog();
        $log5->setDeviceMaintenance($deviceMaintenance5);
        $log5->setCurrentStep($step5);
        $log5->setPreviousStep($step4);
        $log5->setChangedAt(new \DateTime());
        $manager->persist($log5);

        // 7) Flush pour tout enregistrer en base
        $manager->flush();
    }
}

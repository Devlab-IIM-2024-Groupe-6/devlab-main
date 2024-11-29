<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Deposit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher
    )
    {
    }
    public function load(ObjectManager $manager): void
    {
        $deposit = new Deposit();
        $deposit->setName('Curious Lab');
        $deposit->setAddress('Curious Lab Address');
        $deposit->setLatitude(48.8931777);
        $deposit->setLongitude(2.226908);
        $deposit->setSchedules('Curious Lab Schedules');
        $deposit->setDescription('Curious Lab Description');
        
        $manager->persist($deposit);

        $user = new User();
        $user->setEmail('curiouslab@admin.com');
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                'aMLBkroE6gJmS&$$'
            )
        );
        $user->setRoles(['ROLE_ADMIN']);
        $user->setFirstname('Curious');
        $user->setLastname('Lab');
        $user->setDeposit($deposit);

        $manager->persist($user);

        $manager->flush();
    }
}

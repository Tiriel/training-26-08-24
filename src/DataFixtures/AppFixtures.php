<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\TaskFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct()
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = (new User())
            ->setUsername('test')
            ->setRoles(['ROLE_USER'])
            ->setPassword('test')
        ;

        $manager->persist($user);
        $manager->flush();

        TaskFactory::createMany(100, ['createdBy' => $user]);
    }
}

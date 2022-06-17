<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
            $user = new User();
            $user
                ->setEmail('test@gmail.com')
                ->setRoles([])
                ->setPassword('$2y$13$jmwXC9AQOSWwHEsn3VQuv.7BZhy32VZ9AvLQG.2fgXB6an3aZ3Ai.');

            $manager->persist($user);

        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoomFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 9; $i++) {
            $room = new Room();
            $room
                ->setCapacity(mt_rand(1,4))
                ->setPrice((mt_rand(1000, 100000) /100))
                ->setAvailability(true);
            $manager->persist($room);
        }

        $manager->flush();
    }
}

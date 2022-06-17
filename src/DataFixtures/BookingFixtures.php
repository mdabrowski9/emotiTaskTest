<?php

namespace App\DataFixtures;

use App\Entity\Booking;
use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookingFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $fourDay = new \DateInterval('P4D');
        $dateFrom = new \DateTime(date('d-m-Y'));
        $dateTo = $dateFrom->add($fourDay);
        $room = new Room();
        $room
            ->setCapacity(mt_rand(1,4))
            ->setPrice((mt_rand(1000, 100000) /100))
            ->setAvailability(true);
        $manager->persist($room);

        $booking = new Booking();
        $booking
            ->addRoom($room)
            ->setDateFrom($dateFrom)
            ->setDateTo($dateTo)
            ->setUserId(1);

        $manager->persist($booking);

        $manager->flush();
    }
}

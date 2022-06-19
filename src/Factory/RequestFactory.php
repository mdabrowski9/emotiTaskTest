<?php

namespace App\Factory;

use App\Model\RequestBookingAddDto;
use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use DateTime;

class RequestFactory
{
    const FORMAT = 'd-m-Y';
    public function __construct(private RoomRepository $roomRepository)
    {
    }

    public function prepareBookingAddDto(string $content): RequestBookingAddDto
    {
        $requestContent = json_decode($content);
        $roomCollection = new ArrayCollection();
        foreach ($requestContent->roomIds as $roomId){
            $room = $this->roomRepository->find($roomId);
            if(!$room){
                continue;
            }
            $roomCollection->add($room);
        }

        return new RequestBookingAddDto(
            $roomCollection,
            DateTime::createFromFormat(self::FORMAT,$requestContent->dateFrom),
            DateTime::createFromFormat(self::FORMAT,$requestContent->dateTo),
            $requestContent->userId,
        );
    }
}

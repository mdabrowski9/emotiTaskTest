<?php

namespace App\Services;

use App\Entity\Booking;
use App\Model\RequestBookingAddDto;
use App\Repository\BookingRepository;
use DateInterval;
use League\Period\Bounds;
use League\Period\Period;

class AvailabilityService
{
    public function __construct(private BookingRepository $bookingRepository)
    {
    }

    /**
     * @param RequestBookingAddDto $requestBookingAddDto
     * @return bool
     */
    public function isRoomAvailabilityAtThisPeriod(RequestBookingAddDto $requestBookingAddDto): bool
    {
        $oneHourInterval = new DateInterval('PT1H');
        $newBookingPeriod = Period::fromDate($requestBookingAddDto->getDateFrom(),$requestBookingAddDto->getDateTo(), Bounds::IncludeAll);
        $allBooking = $this->bookingRepository->findAll();

        return $this->checkIfRequestedRoomIsAvailableInRequestedTime($requestBookingAddDto, $oneHourInterval, $newBookingPeriod, $allBooking);
    }

    /**
     * @param RequestBookingAddDto $requestBookingAddDto
     * @param DateInterval $oneHourInterval
     * @param Period $newBookingPeriod
     * @param Booking[] $allBooking
     * @return bool
     */
    private function checkIfRequestedRoomIsAvailableInRequestedTime(RequestBookingAddDto $requestBookingAddDto, DateInterval $oneHourInterval, Period $newBookingPeriod,array $allBooking): bool
    {
        foreach ($allBooking as $existingBooking){
            $existingReservation = Period::fromDate($existingBooking->getDateFrom(),$existingBooking->getDateTo(), Bounds::IncludeAll);
            foreach ($newBookingPeriod->splitForward($oneHourInterval) as $interval){
                if ($existingReservation->contains($interval)) {
                    if($this->checkReservationHaveTheSameRoomAsExistingOne($existingBooking, $requestBookingAddDto)){
                        return false;
                    }
                }
            }
        }

        return true;
    }

    /**
     * @param Booking $existingBooking
     * @param RequestBookingAddDto $requestBooking
     * @return bool
     */
    private function checkReservationHaveTheSameRoomAsExistingOne(Booking $existingBooking, RequestBookingAddDto $requestBooking): bool
    {
        if (!$existingBooking->getRooms()->isEmpty()){
            foreach ($requestBooking->getRooms() as $requestRoom){
                return $existingBooking->getRooms()->contains($requestRoom);
            }
        }

        return false;
    }
}

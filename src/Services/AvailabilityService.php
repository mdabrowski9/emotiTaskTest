<?php

namespace App\Services;

use App\Entity\Booking;
use App\Entity\User;
use App\Model\RequestBookingAddDto;
use App\Repository\BookingRepository;
use DateInterval;
use League\Period\Bounds;
use League\Period\Period;
use Symfony\Component\HttpFoundation\JsonResponse;

class AvailabilityService
{
    public function __construct(private BookingRepository $bookingRepository)
    {
    }

    public function addReservation(RequestBookingAddDto $bookingAddDto, User $user): JsonResponse
    {
        if($this->isRoomAvailabilityAtThisPeriod($bookingAddDto)) {
            $booking = new Booking();
            $booking
                ->addRoomsFromCollection($bookingAddDto->getRooms())
                ->setDateFrom($bookingAddDto->getDateFrom())
                ->setDateTo($bookingAddDto->getDateTo())
                ->setUserId($user->getId());
            $this->bookingRepository->add($booking, true);

            return new JsonResponse([
                'message' => 'Reservation was added successfully.'
            ]);
        }

        return new JsonResponse(['message' => 'The booking was unsuccessful.'], 400);
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

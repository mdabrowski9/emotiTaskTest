<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\User;
use App\Factory\RequestFactory;
use App\Repository\BookingRepository;
use App\Services\AvailabilityService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class BookingController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private BookingRepository $bookingRepository,
        private RequestFactory $requestFactory,
        private AvailabilityService $availabilityService,
    )
    {
    }

    #[Route('/booking/list', name: 'app_booking_list', methods: ['GET'])]
    public function index(): Response
    {
        return new Response(
            $this->serializer->serialize($this->bookingRepository->findAll(), JsonEncoder::FORMAT),
            200,
            array_merge([], ['Content-Type' => 'application/json;charset=UTF-8'])
        );
    }

    #[Route('/booking/show/{id}', name: 'app_booking_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(int $id): Response
    {
        return new Response(
            $this->serializer->serialize($this->bookingRepository->find($id), JsonEncoder::FORMAT),
            200,
            array_merge([], ['Content-Type' => 'application/json;charset=UTF-8'])
        );
    }

    #[Route('/booking/add', name: 'app_booking_add', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $bookingAddDto = $this->requestFactory->prepareBookingAddDto($request->getContent());
        if($this->availabilityService->isRoomAvailabilityAtThisPeriod($bookingAddDto)) {
            $booking = new Booking();
            $booking
                ->addRoomsFromCollection($bookingAddDto->getRooms())
                ->setDateFrom($bookingAddDto->getDateFrom())
                ->setDateTo($bookingAddDto->getDateTo())
                ->setUserId($user->getId());
            $this->bookingRepository->add($booking, true);

            return $this->json([
                'message' => 'Reservation was added successfully.'
            ]);
        }

        return new JsonResponse(['message' => 'The booking was unsuccessful.'], 400);
    }

    #[Route('/booking/delete/{id}', name: 'app_booking_delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $booking = $this->bookingRepository->find($id);
        $this->bookingRepository->remove($booking, true);

        return $this->json([
            'message' => 'Reservation was deleted.'
        ]);
    }
}

<?php

namespace App\Controller;

use App\Repository\BookingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
    public function __construct(
        private BookingRepository $bookingRepository
    )
    {
    }

    #[Route('/booking/list', name: 'app_booking_list', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json($this->bookingRepository->findAll());
    }

    #[Route('/booking/show/{id}', name: 'app_booking_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        return $this->json($this->bookingRepository->find($id));
    }

    #[Route('/booking/add', name: 'app_booking_add', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
//        serwis do skladania obiektu
        $requestObject = json_decode($request->getContent());
//        dd(json_decode($request));
        dd(json_decode($request->getContent()));
        return $this->json([
            'message' => 'Booking was added successfully.'
        ]);
    }

    #[Route('/booking/delete/{id}', name: 'app_booking_delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $booking = $this->bookingRepository->find($id);
        $this->bookingRepository->remove($booking);

        return $this->json([
            'message' => 'Reservation was deleted.'
        ]);
    }
}

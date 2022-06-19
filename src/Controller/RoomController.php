<?php

namespace App\Controller;

use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class RoomController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private RoomRepository $roomRepository,
    )
    {
    }

    #[Route('/room/list', name: 'app_room_list', methods: ['GET'])]
    public function index(): Response
    {
        return new Response(
            $this->serializer->serialize($this->roomRepository->findAll(), JsonEncoder::FORMAT),
            200,
            array_merge([], ['Content-Type' => 'application/json;charset=UTF-8'])
        );
    }
}

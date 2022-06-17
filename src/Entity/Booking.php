<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookingRepository::class)]
class Booking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\OneToMany(mappedBy: 'booking', targetEntity: Room::class)]
    private $room_ids;

    public function __construct()
    {
        $this->room_ids = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Room>
     */
    public function getRoomIds(): Collection
    {
        return $this->room_ids;
    }

    public function addRoomId(Room $roomId): self
    {
        if (!$this->room_ids->contains($roomId)) {
            $this->room_ids[] = $roomId;
        }

        return $this;
    }

    public function removeRoomId(Room $roomId): self
    {
        $this->room_ids->removeElement($roomId);

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\TripRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TripRepository::class)]
class Trip
{
    public const STATUS_UPCOMING = 'à venir';
    public const STATUS_ONGOING = 'en cours';
    public const STATUS_COMPLETED = 'effectué';

    public const STATUSES = [
        self::STATUS_UPCOMING,
        self::STATUS_ONGOING,
        self::STATUS_COMPLETED,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Assert\GreaterThanOrEqual('today')]
    private ?\DateTimeImmutable $departureDate = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $arrivalDate = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private ?\DateTimeInterface $departureTime = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private ?\DateTimeInterface $arrivalTime = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    private ?string $departureAddress = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    private ?string $arrivalAddress = null;

    #[ORM\Column(length: 255)]
    #[Assert\Choice(choices: Trip::STATUSES, message: 'Statut invalide.')]
    private ?string $status = self::STATUS_UPCOMING;

    #[ORM\Column]
    #[Assert\GreaterThan(0)]
    #[Assert\LessThanOrEqual(4)]
    private ?int $seatsAvailable = null;

    #[ORM\Column]
    #[Assert\PositiveOrZero()]
    private ?int $pricePerPerson = null;

    #[ORM\ManyToOne(inversedBy: 'trips')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Car $car = null;

    #[ORM\ManyToOne(inversedBy: 'tripsAsDriver')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $driver = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'tripsAsPassenger')]
    #[ORM\JoinTable(name: 'trip_passengers')]
    private Collection $passengers;

    public function __construct()
    {
        $this->passengers = new ArrayCollection();
        $this->status = self::STATUS_UPCOMING;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepartureDate(): ?\DateTimeImmutable
    {
        return $this->departureDate;
    }

    public function setDepartureDate(\DateTimeImmutable $departureDate): static
    {
        $this->departureDate = $departureDate;

        return $this;
    }

    public function getArrivalDate(): ?\DateTimeImmutable
    {
        return $this->arrivalDate;
    }

    public function setArrivalDate(\DateTimeImmutable $arrivalDate): static
    {
        $this->arrivalDate = $arrivalDate;

        return $this;
    }

    public function getArrivalTime(): ?\DateTimeInterface
    {
        return $this->arrivalTime;
    }

    public function setArrivalTime(\DateTimeInterface $arrivalTime): static
    {
        $this->arrivalTime = $arrivalTime;

        return $this;
    }

    public function getDepartureTime(): ?\DateTimeInterface
    {
        return $this->departureTime;
    }

    public function setDepartureTime(\DateTimeInterface $departureTime): static
    {
        $this->departureTime = $departureTime;

        return $this;
    }

    public function getDepartureAddress(): ?string
    {
        return $this->departureAddress;
    }

    public function setDepartureAddress(string $departureAddress): static
    {
        $this->departureAddress = $departureAddress;

        return $this;
    }

    public function getArrivalAddress(): ?string
    {
        return $this->arrivalAddress;
    }

    public function setArrivalAddress(string $arrivalAddress): static
    {
        $this->arrivalAddress = $arrivalAddress;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getSeatsAvailable(): ?int
    {
        return $this->seatsAvailable;
    }

    public function setSeatsAvailable(int $seatsAvailable): static
    {
        $this->seatsAvailable = $seatsAvailable;

        return $this;
    }

    public function getPricePerPerson(): ?int
    {
        return $this->pricePerPerson;
    }

    public function setPricePerPerson(int $pricePerPerson): static
    {
        $this->pricePerPerson = $pricePerPerson;

        return $this;
    }

    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): static
    {
        $this->car = $car;

        return $this;
    }

    public function getDriver(): ?User
    {
        return $this->driver;
    }

    public function setDriver(?User $driver): static
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getPassengers(): Collection
    {
        return $this->passengers;
    }

    public function addPassenger(User $passenger): static
    {
        if (!$this->passengers->contains($passenger)) {
            $this->passengers->add($passenger);
            if (!$passenger->getTripsAsPassenger()->contains($this)) {
                $passenger->addTripsAsPassenger($this);
            }
        }

        return $this;
    }

    public function removePassenger(User $passenger): static
    {
        if ($this->passengers->removeElement($passenger)) {
            if ($passenger->getTripsAsPassenger()->contains($this)) {
                $passenger->removeTripsAsPassenger($this);
            }
        }

        return $this;
    }

        public function __toString(): string
    {
        return sprintf('%s à %s (%s)', $this->departureAddress, $this->arrivalAddress, $this->departureDate?->format('d/m/Y'));
    }
}

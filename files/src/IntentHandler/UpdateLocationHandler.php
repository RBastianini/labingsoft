<?php

declare(strict_types=1);

namespace App\IntentHandler;

use App\Entity\Location;
use App\Intent\UpdateLocationIntent;
use Doctrine\ORM\EntityManagerInterface;

class UpdateLocationHandler
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function handle(UpdateLocationIntent $updateLocation): Location
    {
        $location = $updateLocation->location;
        $location->setCountry($updateLocation->locationDTO->country);
        $location->setName($updateLocation->locationDTO->name);
        $location->setLatitude($updateLocation->locationDTO->latitude);
        $location->setLongitude($updateLocation->locationDTO->longitude);
        $this->entityManager->flush();

        return $location;
    }
}

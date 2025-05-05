<?php

declare(strict_types=1);

namespace App\IntentHandler;

use App\Entity\Location;
use App\Intent\CreateLocationIntent;
use Doctrine\ORM\EntityManagerInterface;

class CreateLocationHandler
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function handle(CreateLocationIntent $createLocation): Location
    {
        $location = new Location($createLocation->locationDTO->name, $createLocation->locationDTO->country);
        $location->setLatitude($createLocation->locationDTO->latitude);
        $location->setLongitude($createLocation->locationDTO->longitude);
        $this->entityManager->persist($location);
        $this->entityManager->flush();

        return $location;
    }
}

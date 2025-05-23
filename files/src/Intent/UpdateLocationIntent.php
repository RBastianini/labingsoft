<?php

declare(strict_types=1);

namespace App\Intent;

use App\DTO\LocationDTO;
use App\Entity\Location;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateLocationIntent
{
    #[Assert\Valid()]
    public LocationDTO $locationDTO;

    public readonly Location $location;

    public function __construct(Location $location)
    {
        $this->location = $location;
        $this->locationDTO = new LocationDTO();
        $this->locationDTO->name = $location->getName();
        // Hack: avremmo dovuto salvare lo stato maiuscolo nel db per farlo riconoscere al CountryType field...
        // Convertiamo in maiuscolo qua.
        $this->locationDTO->country = mb_strtoupper($location->getCountry());
        $this->locationDTO->latitude = $location->getLatitude();
        $this->locationDTO->longitude = $location->getLongitude();
    }
}

<?php

declare(strict_types=1);

namespace App\Intent;

use App\DTO\LocationDTO;

class CreateLocationIntent
{
    public LocationDTO $locationDTO;

    public function __construct()
    {
        $this->locationDTO = new LocationDTO();
    }
}

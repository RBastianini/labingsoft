<?php

declare(strict_types=1);

namespace App\Intent;

use App\DTO\LocationDTO;
use Symfony\Component\Validator\Constraints as Assert;

class CreateLocationIntent
{
    #[Assert\Valid()]
    public LocationDTO $locationDTO;

    public function __construct()
    {
        $this->locationDTO = new LocationDTO();
    }
}

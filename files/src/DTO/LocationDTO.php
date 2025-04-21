<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class LocationDTO
{
    #[Assert\NotBlank]
    public ?string $name;

    #[Assert\NotBlank]
    public ?string $country;

    #[Assert\Range(min: -90, max: 90)]
    #[Assert\Length(max: 11)]
    public ?string $latitude = null;

    #[Assert\Range(min: -180, max: 180)]
    #[Assert\Length(max: 12)]
    public ?string $longitude = null;
}

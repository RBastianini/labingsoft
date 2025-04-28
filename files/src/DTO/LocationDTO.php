<?php

declare(strict_types=1);

namespace App\DTO;

use App\Validator as MyAssert;
use Symfony\Component\Validator\Constraints as Assert;

#[MyAssert\BothPropertiesPresent(
    property1: 'latitude',
    property2: 'longitude',
    message: 'both_coordinates_must_be_defined_or_none_defined'
)]
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

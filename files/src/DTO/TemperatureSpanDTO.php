<?php

declare(strict_types=1);

namespace App\DTO;

use App\Validator as MyAssert;

#[MyAssert\BothPropertiesPresent(
    property1: 'minimumCelsiusTemperature',
    property2: 'maximumCelsiusTemperature'
)]
class TemperatureSpanDTO
{
    public ?int $minimumCelsiusTemperature = null;

    public ?int $maximumCelsiusTemperature = null;
}

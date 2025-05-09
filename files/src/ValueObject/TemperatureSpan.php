<?php

declare(strict_types=1);

namespace App\ValueObject;

use Assert\Assertion;

class TemperatureSpan
{
    private int $minimumCelsiusTemperature;

    private int $maximumCelsiusTemperature;

    public function __construct(int $minimumCelsiusTemperature, int $maximumCelsiusTemperature)
    {
        Assertion::lessOrEqualThan(
            $minimumCelsiusTemperature,
            $maximumCelsiusTemperature,
            'Minimum celsius temperature must be less or equal than maximum celsius temperature'
        );
        $this->minimumCelsiusTemperature = $minimumCelsiusTemperature;
        $this->maximumCelsiusTemperature = $maximumCelsiusTemperature;
    }

    public function getMaximumCelsiusTemperature(): int
    {
        return $this->maximumCelsiusTemperature;
    }

    public function getMinimumCelsiusTemperature(): int
    {
        return $this->minimumCelsiusTemperature;
    }
}

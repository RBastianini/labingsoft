<?php

declare(strict_types=1);

namespace App\Tests\Unit\ValueObject;

use App\ValueObject\TemperatureSpan;
use PHPUnit\Framework\TestCase;

class TemperatureSpanTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_the_values_passed_in_construction(): void
    {
        $SUT = new TemperatureSpan(0, 10);

        $this->assertSame(0, $SUT->getMinimumCelsiusTemperature());
        $this->assertSame(10, $SUT->getMaximumCelsiusTemperature());
    }

    /**
     * @test
     * @dataProvider provider_values_in_construction_2
     */
    public function it_returns_the_values_passed_in_construction2(int $minTemp, int $maxTemp): void
    {
        $SUT = new TemperatureSpan($minTemp, $maxTemp);

        $this->assertSame($minTemp, $SUT->getMinimumCelsiusTemperature());
        $this->assertSame($maxTemp, $SUT->getMaximumCelsiusTemperature());
    }

    public function provider_values_in_construction_2()
    {
        return [
            [0, 10],
            [0, 0],
        ];
    }

    /**
     * @test
     */
    public function it_cannot_be_constructed_if_temperatures_are_invalid(): void
    {
        $this->expectExceptionMessage(
            'Minimum celsius temperature must be less or equal than maximum celsius temperature'
        );
        new TemperatureSpan(10, 0);
    }
}

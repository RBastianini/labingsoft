<?php

declare(strict_types=1);

namespace App\Tests\Unit\Validator;

use PHPUnit\Framework\TestCase;

class BothPropertiesPresentValidatorTest extends TestCase
{
    /**
     * @test
     */
    public function it_checks_that_property1_is_readable(): void
    {
    }

    /**
     * @test
     */
    public function it_checks_that_property2_is_readable(): void
    {
    }

    /**
     * @test
     */
    public function it_passes_validation_if_both_values_are_null(): void
    {
    }

    /**
     * @test
     */
    public function it_passes_validation_if_both_values_are_not_null(): void
    {
    }

    /**
     * @test
     */
    public function it_fails_validation_if_only_one_value_is_defined(): void
    {
    }
}

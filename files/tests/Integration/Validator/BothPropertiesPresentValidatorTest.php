<?php

declare(strict_types=1);

namespace App\Tests\Integration\Validator;

use App\Validator\BothPropertiesPresent;
use App\Validator\BothPropertiesPresentValidator;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\InvalidArgumentException;

/**
 * @covers \App\Validator\BothPropertiesPresentValidator
 */
class BothPropertiesPresentValidatorTest extends KernelTestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @test
     */
    public function it_validates_an_object_where_both_properties_are_null(): void
    {
        /** @var BothPropertiesPresentValidator $SUT */
        $SUT = $this->getContainer()->get(BothPropertiesPresentValidator::class);
        $context = \Mockery::spy(ExecutionContextInterface::class);
        $SUT->initialize($context);

        $valueToValidate = new class {
            public ?int $someProperty = null;
            public ?int $someOtherProperty = null;
        };

        $SUT->validate(
            $valueToValidate,
            new BothPropertiesPresent(
                property1: 'someProperty',
                property2: 'someOtherProperty',
            )
        );

        $context->shouldNotHaveReceived('buildViolation');
    }

    /**
     * @test
     */
    public function it_throws_an_exception_when_a_property_does_not_exist(): void
    {
        /** @var BothPropertiesPresentValidator $SUT */
        $SUT = $this->getContainer()->get(BothPropertiesPresentValidator::class);
        $context = \Mockery::spy(ExecutionContextInterface::class);
        $SUT->initialize($context);

        $valueToValidate = new class {
            public ?int $someProperty = null;
            public ?int $someOtherProperty = null;
        };

        $wrongProperty = 'thisPropertyDoesNotExist';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Property $wrongProperty is not accessible on passed object.");

        $SUT->validate(
            $valueToValidate,
            new BothPropertiesPresent(
                property1: $wrongProperty,
                property2: 'someOtherProperty',
            )
        );
    }
}

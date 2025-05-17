<?php

declare(strict_types=1);

namespace App\Tests\Unit\Validator;

use App\Validator\BothPropertiesPresent;
use App\Validator\BothPropertiesPresentValidator;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class BothPropertiesPresentValidatorTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private BothPropertiesPresentValidator $SUT;

    private PropertyAccessorInterface&MockInterface $propertyAccessor;

    private ExecutionContextInterface&MockInterface $context;

    protected function setUp(): void
    {
        parent::setUp();
        $this->propertyAccessor = \Mockery::mock(PropertyAccessorInterface::class);
        $this->context = \Mockery::spy(ExecutionContextInterface::class);
        $this->SUT = new BothPropertiesPresentValidator($this->propertyAccessor);
        $this->SUT->initialize($this->context);
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_property1_is_not_readable(): void
    {
        $valueToValidate = new \stdClass();
        $property1 = 'someProperty';
        $property2 = 'someOtherProperty';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Property {$property1} is not accessible on passed object.");

        $this->propertyAccessor->expects('isReadable')
            ->with($valueToValidate, $property1)
            ->andReturn(false);

        $this->SUT->validate(
            $valueToValidate,
            new BothPropertiesPresent(
                property1: $property1,
                property2: $property2
            )
        );
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_property2_is_not_readable(): void
    {
        $valueToValidate = new \stdClass();
        $property1 = 'someProperty';
        $property2 = 'someOtherProperty';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Property {$property2} is not accessible on passed object.");

        $this->propertyAccessor->expects('isReadable')
            ->with($valueToValidate, $property1)
            ->andReturn(true);

        $this->propertyAccessor->expects('isReadable')
            ->with($valueToValidate, $property2)
            ->andReturn(false);

        $this->SUT->validate(
            $valueToValidate,
            new BothPropertiesPresent(
                property1: $property1,
                property2: $property2
            )
        );
    }

    /**
     * @test
     */
    public function it_passes_validation_if_both_values_are_null(): void
    {
        $valueToValidate = new \stdClass();
        $property1 = 'someProperty';
        $property2 = 'someOtherProperty';

        $this->propertyAccessor->expects('isReadable')
            ->with($valueToValidate, $property1)
            ->andReturn(true);

        $this->propertyAccessor->expects('isReadable')
            ->with($valueToValidate, $property2)
            ->andReturn(true);

        $this->propertyAccessor->expects('getValue')
            ->with($valueToValidate, $property1)
            ->andReturn(null);

        $this->propertyAccessor->expects('getValue')
            ->with($valueToValidate, $property2)
            ->andReturn(null);

        $this->SUT->validate(
            $valueToValidate,
            new BothPropertiesPresent(
                property1: $property1,
                property2: $property2
            )
        );

        $this->context->shouldNotHaveReceived('buildViolation');
    }

    /**
     * @test
     */
    public function it_passes_validation_if_both_values_are_not_null(): void
    {
        $property1Name = 'someProperty';
        $property2Name = 'someOtherProperty';
        $valueToValidate = new \stdClass();

        // Arrange
        $this->propertyAccessor->expects('isReadable')
            ->with($valueToValidate, $property1Name)
            ->andReturn(true);

        $this->propertyAccessor->expects('isReadable')
            ->with($valueToValidate, $property2Name)
            ->andReturn(true);

        $this->propertyAccessor->expects('getValue')
            ->with($valueToValidate, $property1Name)
            ->andReturn('something');

        $this->propertyAccessor->expects('getValue')
            ->with($valueToValidate, $property2Name)
            ->andReturn('something else');

        // Act
        $this->SUT->validate(
            $valueToValidate,
            new BothPropertiesPresent(property1: $property1Name, property2: $property2Name)
        );

        // Assert
        $this->context->shouldNotHaveReceived('buildViolation');
    }

    /**
     * @test
     */
    public function it_fails_validation_if_only_one_value_is_defined(): void
    {
        $valueToValidate = new \stdClass();
        $property1 = 'someProperty';
        $property2 = 'someOtherProperty';

        $this->propertyAccessor->expects('isReadable')
            ->with($valueToValidate, $property1)
            ->andReturn(true);

        $this->propertyAccessor->expects('isReadable')
            ->with($valueToValidate, $property2)
            ->andReturn(true);

        $this->propertyAccessor->expects('getValue')
            ->with($valueToValidate, $property1)
            ->andReturn(42);

        $this->propertyAccessor->expects('getValue')
            ->with($valueToValidate, $property2)
            ->andReturn(null);

        $message = 'validation error';

        $this->context->expects('buildViolation')
            ->with($message)
            ->andReturn($builder = \Mockery::mock(ConstraintViolationBuilderInterface::class));

        $builder->expects('setParameter')
            ->with('%property1%', $property1)
            ->andReturnSelf();

        $builder->expects('setParameter')
            ->with('%property2%', $property2)
            ->andReturnSelf();

        $builder->expects('addViolation');

        $this->SUT->validate(
            $valueToValidate,
            new BothPropertiesPresent(
                property1: $property1,
                property2: $property2,
                message: $message,
            )
        );
    }
}

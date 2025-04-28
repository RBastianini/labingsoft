<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class BothPropertiesPresentValidator extends ConstraintValidator
{
    private PropertyAccessorInterface $propertyAccessor;

    public function __construct(PropertyAccessorInterface $propertyAccessor)
    {
        $this->propertyAccessor = $propertyAccessor;
    }

    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof BothPropertiesPresent) {
            throw new UnexpectedTypeException($constraint, BothPropertiesPresent::class);
        }
        if (!is_object($value)) {
            throw new UnexpectedTypeException($value, 'object');
        }

        if (!$this->propertyAccessor->isReadable($value, $constraint->property1)) {
            throw new InvalidArgumentException("Property {$constraint->property1} is not accessible on passed object.");
        }
        if (!$this->propertyAccessor->isReadable($value, $constraint->property2)) {
            throw new InvalidArgumentException("Property {$constraint->property2} is not accessible on passed object.");
        }
        $value1 = $this->propertyAccessor->getValue($value, $constraint->property1);
        $value2 = $this->propertyAccessor->getValue($value, $constraint->property2);

        if (
            (null === $value1 && null === $value2)
            || (null !== $value1 && null !== $value2)
        ) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('%property1%', $constraint->property1)
            ->setParameter('%property2%', $constraint->property2)
            ->addViolation();
    }
}

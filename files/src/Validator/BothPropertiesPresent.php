<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute()]
class BothPropertiesPresent extends Constraint
{
    public string $message = 'both_properties_%property1%_%property2%_must_be_present_or_both_absent';

    public string $property1;

    public string $property2;

    /**
     * @param mixed[]|null $groups
     * @param mixed[]|null $payload
     */
    public function __construct(
        string $property1,
        string $property2,
        ?string $message = null,
        ?array $groups = null,
        ?array $payload = null,
    ) {
        parent::__construct([], $groups, $payload);
        $this->property1 = $property1;
        $this->property2 = $property2;
        $this->message = $message ?? $this->message;
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}

<?php
namespace App\Shared\Domain\ValueObject;

abstract class FloatValueObject
{
    protected float $value;

    public function __construct(float $value)
    {
        $this->value = $value;
    }

    public function value(): float
    {
        return $this->value;
    }

    public function isBiggerThan(FloatValueObject $other): bool
    {
        return $this->value() > $other->value();
    }
}

<?php declare (strict_types=1);

namespace App\VendingMachine\Domain\Product\ValueObject;

use App\Shared\Domain\ValueObject\FloatValueObject;

final class ProductPrice extends FloatValueObject
{
    /**
     * @param float $value
     * @return self
     */
    public static function build(float $value): ProductPrice
    {
        return new self($value);
    }
}

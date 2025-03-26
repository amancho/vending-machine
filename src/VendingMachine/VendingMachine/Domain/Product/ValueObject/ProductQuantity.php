<?php declare (strict_types=1);

namespace App\VendingMachine\Domain\Product\ValueObject;

use App\Shared\Domain\ValueObject\IntValueObject;

final class ProductQuantity extends IntValueObject
{
    /**
     * @param int $value
     * @return self
     */
    public static function build(int $value): ProductQuantity
    {
        return new self($value);
    }
}

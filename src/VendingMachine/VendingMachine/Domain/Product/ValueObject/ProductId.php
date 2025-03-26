<?php declare (strict_types=1);

namespace App\VendingMachine\Domain\Product\ValueObject;

use App\Shared\Domain\ValueObject\IntValueObject;

final class ProductId extends IntValueObject
{
    /**
     * @param int $value
     * @return self
     */
    public static function build(int $value): ProductId
    {
        return new self($value);
    }
}

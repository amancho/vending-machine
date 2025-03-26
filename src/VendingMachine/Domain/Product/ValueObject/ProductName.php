<?php declare (strict_types=1);

namespace App\VendingMachine\Domain\Product\ValueObject;

use App\Shared\Domain\ValueObject\StringValueObject;

final class ProductName extends StringValueObject
{
    /**
     * @param string $value
     * @return self
     */
    public static function build(string $value): ProductName
    {
        return new self($value);
    }
}

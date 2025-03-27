<?php declare (strict_types=1);

namespace App\VendingMachine\Domain\Coin\ValueObject;

use App\Shared\Domain\ValueObject\FloatValueObject;

final class CoinAmount extends FloatValueObject
{
    /**
     * @param float $value
     * @return self
     */
    public static function build(float $value): CoinAmount
    {
        return new self($value);
    }
}

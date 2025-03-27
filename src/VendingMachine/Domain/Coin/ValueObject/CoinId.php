<?php declare (strict_types=1);

namespace App\VendingMachine\Domain\Coin\ValueObject;

use App\Shared\Domain\ValueObject\IntValueObject;

final class CoinId extends IntValueObject
{
    /**
     * @param int $value
     * @return self
     */
    public static function build(int $value): CoinId
    {
        return new self($value);
    }
}

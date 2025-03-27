<?php declare (strict_types=1);

namespace App\VendingMachine\Domain\Coin;

use App\Shared\Domain\Collection;
use App\Shared\Domain\InvalidCollectionObjectException;

class CoinCollection extends Collection
{
    protected function type(): string
    {
        return Coin::class;
    }

    /**
     * @throws InvalidCollectionObjectException
     */
    public static function init(): self
    {
        return new CoinCollection([]);
    }

    /**
     * @throws InvalidCollectionObjectException
     */
    public static function create(array $coins): self
    {
        return new self($coins);
    }
}

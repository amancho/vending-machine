<?php declare (strict_types=1);

namespace App\VendingMachine\Domain\Coin;

use App\Shared\Domain\InvalidCollectionObjectException;

interface CoinRepository
{
    /**
     * @throws InvalidCollectionObjectException
     */
    public function getAll(): CoinCollection;
}

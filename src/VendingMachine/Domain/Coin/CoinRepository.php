<?php declare (strict_types=1);

namespace App\VendingMachine\Domain\Coin;

use App\Shared\Domain\InvalidCollectionObjectException;
use App\VendingMachine\Domain\Coin\Enum\CoinStatusEnum;

interface CoinRepository
{
    /**
     * @throws InvalidCollectionObjectException
     */
    public function getAll(): CoinCollection;

    public function getByStatus(CoinStatusEnum $status): array;
}

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

    public function getByMultipleStatus(string $status): array;

    public function getByStatus(CoinStatusEnum $status): array;

    public function insert(float $value, CoinStatusEnum $status): void;

    public function deleteByStatus(CoinStatusEnum $status): void;

    public function storeByStatus(CoinStatusEnum $status): void;

    public function updateStatusByValue(float $value, CoinStatusEnum $status, int $limit): void;
}

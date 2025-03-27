<?php declare (strict_types=1);

namespace App\VendingMachine\Domain\Coin;

class CoinService
{
    private array $allowedCoins = [0.05, 0.10, 0.25, 1];

    public function check(float $value): bool
    {
        return in_array($value, $this->allowedCoins, true);
    }
}
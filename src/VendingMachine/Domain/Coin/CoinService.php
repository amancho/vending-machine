<?php declare (strict_types=1);

namespace App\VendingMachine\Domain\Coin;

use App\VendingMachine\Domain\Coin\Errors\CoinNotAllowed;

class CoinService
{
    public function __construct(private readonly CoinRepository $coinRepository)
    {
    }

    private array $allowedCoins = [0.05, 0.10, 0.25, 1];

    public function check(float $value): bool
    {
        return in_array($value, $this->allowedCoins);
    }

    /**
     * @throws CoinNotAllowed
     */
    public function insert(float $value): string
    {
        if (!$this->check($value)) {
            throw new CoinNotAllowed();
        }

        $this->coinRepository->insert($value);

        return 'You have insert a ' . $value . ' coin.';
    }
}

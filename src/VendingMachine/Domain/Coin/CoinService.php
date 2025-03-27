<?php declare (strict_types=1);

namespace App\VendingMachine\Domain\Coin;

use App\VendingMachine\Domain\Coin\Enum\CoinStatusEnum;
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

    function canGiveChange(float $change, array $coins): bool
    {
        foreach ($coins as $coin => $count) {
            while ($change >= $coin && $count > 0) {
                $change = round($change - floatval($coin), 2);
                $coins[$coin]--;
            }
        }

        return $change == 0.00;
    }

    function getCoins(CoinStatusEnum $status): array
    {
        $storedCoins = $this->coinRepository->getByStatus($status);

        $result = [];
        foreach ($storedCoins as $coin) {
            $key = number_format($coin['value'], 2, '.', '');
            $result[$key] = intval($coin['total']);
        }

        return $result;
    }
}

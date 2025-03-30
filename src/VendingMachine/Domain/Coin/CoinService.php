<?php declare (strict_types=1);

namespace App\VendingMachine\Domain\Coin;

use App\VendingMachine\Domain\Coin\Enum\CoinStatusEnum;
use App\VendingMachine\Domain\Coin\Errors\CoinNotAllowed;

class CoinService
{
    public function __construct(private readonly CoinRepository $coinRepository)
    {
    }

    private array $allowedCoins = [1, 0.25, 0.10, 0.05];

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

        $this->coinRepository->insert($value, CoinStatusEnum::AVAILABLE);

        return 'You have insert a ' . $value . ' coin.';
    }

    /**
     * @throws CoinNotAllowed
     */
    public function add(float $value, int $quantity): void
    {
        if (!$this->check($value)) {
            throw new CoinNotAllowed();
        }

        for($i=0; $i < $quantity; $i++) {
            $this->coinRepository->insert($value, CoinStatusEnum::STORED);
        }
    }

    public function back(CoinStatusEnum $status): array
    {
        $coins = $this->coinRepository->getByStatus($status);
        $this->coinRepository->deleteByStatus($status);

        return $coins;
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

    function calculateCoinsChange(float $amount): array
    {
        $result = [];

        $coinsStores = $this->coinRepository->getByStatus(CoinStatusEnum::STORED);
        $coinsAvailable = $this->coinRepository->getByStatus(CoinStatusEnum::AVAILABLE);
        $coins = array_merge($coinsStores, $coinsAvailable);

        foreach ($coins as $allowedCoin) {
            $quantity = floor($amount / floatval($allowedCoin));
            if ($quantity > 0) {
                $result[(string) $allowedCoin] = $quantity;
                $amount -= $quantity * $allowedCoin;
                $amount = round($amount, 2);
            }
        }

        return $result;
    }

    function getCoins(CoinStatusEnum $status): array
    {
        $storedCoins = $this->coinRepository->getByStatus($status);

        $result = [];
        foreach ($storedCoins as $coin) {
            $key = $this->getValueOfCoin($coin);
            $result[$key] = $this->getNumberOfCoins($coin);
        }

        return $result;
    }

    function getAvailableAmount(array $coins): float
    {
        $total = 0;
        foreach ($coins as $value => $quantity) {
            $total += floatval(floatval($value) * intval($quantity));
        }

        return $total;
    }

    function store(): void
    {
        $this->coinRepository->updateByStatus(CoinStatusEnum::STORED);
    }

    function returnChange(array $coinsToReturn): void
    {
        foreach ($coinsToReturn as $coin) {
            $value = (float) $coin['value'];
            $quantity = (int) $coin['quantity'];

            $this->coinRepository->updateStatusByValue($value, CoinStatusEnum::RETURNED, $quantity);
        }
    }

    private function getNumberOfCoins(array $coin): int
    {
        return intval($coin['total']);
    }

    private function getValueOfCoin(array $coin): string
    {
        return number_format($coin['value'], 2, '.', '');
    }
}

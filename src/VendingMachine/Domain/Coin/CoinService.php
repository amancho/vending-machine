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
        krsort($coins, SORT_NUMERIC);

        foreach ($coins as $coin => $count) {
            $coin = (float)$coin;
            $maxUsable = min(floor($change / $coin), $count);
            $change = round($change - ($maxUsable * $coin), 2);
        }

        return $change == 0.00;
    }

    function calculateCoinsChange(float $amount, array $coins): array
    {
        $result = [];

        foreach ($coins as $coin => $count) {
            $quantity = floor($amount / floatval($coin));
            if ($quantity > 0) {
                $result[(string) $coin] = $quantity;
                $amount -= $quantity * $coin;
                $amount = round($amount, 2);
            }
        }

        return $result;
    }

    function getAvailableCoins(): array
    {
        $availableCoins = $this->coinRepository->getByStatus(CoinStatusEnum::AVAILABLE);
        return $this->getCoins($availableCoins);
    }

    function getObtainableCoins(): array
    {
        $status = "'" . CoinStatusEnum::AVAILABLE->value . "', '" . CoinStatusEnum::STORED->value . "'";
        $obtainableCoins = $this->coinRepository->getByMultipleStatus($status);

        return $this->getCoins($obtainableCoins);
    }

    function getCoins(array $coins): array
    {
        $result = [];
        foreach ($coins as $coin) {
            $key = $this->getValueOfCoin($coin);
            $result[$key] = $this->getNumberOfCoins($coin);
        }

        return $result;
    }

    function getAmount(array $coins): float
    {
        $total = 0;
        foreach ($coins as $value => $quantity) {
            $total += floatval(floatval($value) * intval($quantity));
        }

        return $total;
    }

    function store(): void
    {
        $this->coinRepository->storeByStatus(CoinStatusEnum::STORED);
    }

    function returnChange(array $coinsToReturn): void
    {
        foreach ($coinsToReturn as $value => $quantity) {

            $this->coinRepository->updateStatusByValue(
                floatval($value),
                CoinStatusEnum::RETURNED,
                intval($quantity)
            );
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

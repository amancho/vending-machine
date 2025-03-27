<?php declare (strict_types=1);

namespace App\VendingMachine\Application\Query\Coin\GetCoins;

use App\Shared\Domain\Bus\Query\QueryResponse;

final readonly class GetCoinsResponse implements QueryResponse
{
    public function __construct(private array $coins)
    {
    }

    public static function build(array $coins): GetCoinsResponse
    {
        return new self($coins);
    }

    public function toArray(): array
    {
        return $this->coins;
    }

    public function report(): string
    {
        $report = 'Coin     | Quantity | Amount' . PHP_EOL;
        $report .= '-----------------------------' . PHP_EOL;

        foreach ($this->coins as $coin) {
            $report .= $this->format(strval($coin['value']), 8)
                . ' | ' . $this->format(strval($coin['total']), 8)
                . ' | ' . $this->getAmount(floatval($coin['value']), intval($coin['total']))  . PHP_EOL ;
        }

        return $report;
    }

    private function getAmount(float $value, int $total): string
    {
        return strval($value * $total);
    }

    private function format(string $type, int $length = 7): string
    {
        return substr($type . '        ', 0, $length);
    }
}

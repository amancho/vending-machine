<?php declare (strict_types=1);

namespace App\VendingMachine\Application\Command;

use App\Shared\Domain\Bus\Command\Command;

readonly class ServiceAddCoinCommand implements Command
{
    public function __construct(
        private float $value,
        private int $quantity
    )
    {
    }

    public static function create(float $value, int $quantity): ServiceAddCoinCommand
    {
        return new self($value, $quantity);
    }

    public function value(): float
    {
        return $this->value;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }
}

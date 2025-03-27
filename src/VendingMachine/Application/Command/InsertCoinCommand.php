<?php declare (strict_types=1);

namespace App\VendingMachine\Application\Command;

use App\Shared\Domain\Bus\Command\Command;
readonly class InsertCoinCommand implements Command
{
    public function __construct(private float $value)
    {
    }

    public static function create(float $value): InsertCoinCommand
    {
        return new self($value);
    }

    public function value(): float
    {
        return $this->value;
    }
}

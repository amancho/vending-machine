<?php declare (strict_types=1);

namespace App\VendingMachine\Application\Command;

use App\Shared\Domain\Bus\Command\Command;

readonly class ServiceAddProductCommand implements Command
{
    public function __construct(
        private string $name,
        private int $quantity
    )
    {
    }

    public static function create(string $name, int $quantity): ServiceAddProductCommand
    {
        return new self($name, $quantity);
    }

    public function name(): string
    {
        return strtolower(trim($this->name));
    }

    public function quantity(): int
    {
        return $this->quantity;
    }
}

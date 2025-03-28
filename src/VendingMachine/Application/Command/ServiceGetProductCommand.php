<?php declare (strict_types=1);

namespace App\VendingMachine\Application\Command;

use App\Shared\Domain\Bus\Command\Command;

readonly class ServiceGetProductCommand implements Command
{
    public function __construct(private string $product)
    {
    }

    public static function create(string $product): ServiceGetProductCommand
    {
        return new self($product);
    }

    public function product(): string
    {
        return strtolower(trim($this->product));
    }
}

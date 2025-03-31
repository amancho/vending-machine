<?php declare (strict_types=1);

namespace App\VendingMachine\Application\Command;

use App\Shared\Domain\Bus\Command\CommandHandler;
use App\VendingMachine\Domain\Product\ProductService;

readonly class ServiceAddProductCommandHandler implements CommandHandler
{
    public function __construct(private ProductService $productService)
    {
    }

    public function handle(ServiceAddProductCommand $command): string
    {
        $this->productService->add($command->name(), $command->quantity());
        return $this->buildMessage($command->name(), $command->quantity());
    }

    private function buildMessage(string $name, int $quantity): string
    {
        return sprintf("Add %s %u items", $name, $quantity);
    }
}

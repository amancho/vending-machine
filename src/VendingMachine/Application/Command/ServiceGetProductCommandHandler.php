<?php declare (strict_types=1);

namespace App\VendingMachine\Application\Command;

use App\Shared\Domain\Bus\Command\CommandHandler;
use App\VendingMachine\Domain\Product\ProductRepository;
use App\VendingMachine\Domain\Product\ProductService;

readonly class ServiceGetProductCommandHandler implements CommandHandler
{
    public function __construct(private ProductRepository $productRepository)
    {
    }

    public function handle(ServiceGetProductCommand $command): string
    {
        $productService = new ProductService($this->productRepository);
        $productService->get($command->product());

        return $this->buildMessage($command->product());
    }

    private function buildMessage(string $name): string
    {
        return sprintf("Get %s", $name);
    }
}

<?php declare (strict_types=1);

namespace App\VendingMachine\Application\Command;

use App\Shared\Domain\Bus\Command\CommandHandler;
use App\VendingMachine\Application\Service\GetProductService;

readonly class GetProductCommandHandler implements CommandHandler
{
    public function __construct(private GetProductService $getProductService)
    {
    }

    public function handle(GetProductCommand $command): string
    {
        $this->getProductService->execute($command->product());

        return $this->buildMessage($command->product());
    }

    private function buildMessage(string $name): string
    {
        return sprintf("Get %s", $name);
    }
}

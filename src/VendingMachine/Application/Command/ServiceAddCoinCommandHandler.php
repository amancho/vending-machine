<?php declare (strict_types=1);

namespace App\VendingMachine\Application\Command;

use App\Shared\Domain\Bus\Command\CommandHandler;
use App\VendingMachine\Domain\Coin\CoinService;

readonly class ServiceAddCoinCommandHandler implements CommandHandler
{
    public function __construct(private CoinService $coinService)
    {
    }

    public function handle(ServiceAddCoinCommand $command): string
    {
        $this->coinService->add($command->value(), $command->quantity());
        return $this->buildMessage($command->value(), $command->quantity());
    }

    private function buildMessage(float $value, int $quantity): string
    {
        return sprintf("Add %u coins of %s", $quantity, $value);
    }
}

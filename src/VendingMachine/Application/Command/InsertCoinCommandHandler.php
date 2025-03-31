<?php declare (strict_types=1);

namespace App\VendingMachine\Application\Command;

use App\Shared\Domain\Bus\Command\CommandHandler;
use App\VendingMachine\Domain\Coin\Coin;
use App\VendingMachine\Domain\Coin\CoinService;
use App\VendingMachine\Domain\Coin\Enum\CoinStatusEnum;

readonly class InsertCoinCommandHandler implements CommandHandler
{
    public function __construct(private CoinService $coinService)
    {
    }

    public function handle(InsertCoinCommand $command): string
    {
        $this->coinService->insert(
            Coin::build(0, $command->value(), CoinStatusEnum::AVAILABLE->value)
        );

        return $this->buildMessage($command->value());
    }

    private function buildMessage(float $coinValue): string
    {
        return 'You have insert a ' . $coinValue . ' coin.';
    }
}

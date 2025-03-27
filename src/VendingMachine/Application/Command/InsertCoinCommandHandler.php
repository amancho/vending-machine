<?php declare (strict_types=1);

namespace App\VendingMachine\Application\Command;

use App\Shared\Domain\Bus\Command\CommandHandler;
use App\VendingMachine\Domain\Coin\CoinRepository;
use App\VendingMachine\Domain\Coin\CoinService;

readonly class InsertCoinCommandHandler implements CommandHandler
{
    public function __construct(private CoinRepository $coinRepository)
    {
    }

    public function handle(InsertCoinCommand $command): string
    {
        $coinService = new CoinService($this->coinRepository);
        return $coinService->insert($command->value());
    }
}

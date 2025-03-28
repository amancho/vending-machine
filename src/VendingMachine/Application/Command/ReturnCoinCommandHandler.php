<?php declare (strict_types=1);

namespace App\VendingMachine\Application\Command;

use App\Shared\Domain\Bus\Command\CommandHandler;
use App\VendingMachine\Domain\Coin\CoinRepository;
use App\VendingMachine\Domain\Coin\CoinService;

readonly class ReturnCoinCommandHandler implements CommandHandler
{
    public function __construct(private CoinRepository $coinRepository)
    {
    }

    public function handle(ReturnCoinCommand $command): string
    {
        $coinService = new CoinService($this->coinRepository);
        $coins = $coinService->back($command->status());

        return $this->buildMessage($coins);
    }

    private function buildMessage(array $coins): string
    {
        if (empty($coins)) {
            return 'You have no coins to return.';
        }

        $message = 'Coins to return' . PHP_EOL;
        foreach ($coins as $coin) {
            $message .= $coin['total'] . ' coins of ' .
                number_format($coin['value'], 2, '.', '') . PHP_EOL;
        }

        return $message;
    }
}

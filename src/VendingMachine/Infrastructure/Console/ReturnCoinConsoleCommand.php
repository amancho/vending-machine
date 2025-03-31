<?php declare (strict_types=1);

namespace App\VendingMachine\Infrastructure\Console;

use App\VendingMachine\Application\Command\ReturnCoinCommand;
use App\VendingMachine\Application\Command\ReturnCoinCommandHandler;
use App\VendingMachine\Domain\Coin\CoinService;
use App\VendingMachine\Domain\Coin\Enum\CoinStatusEnum;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ReturnCoinConsoleCommand extends Command
{
    public function __construct(private readonly CoinService $coinService)
    {
        parent::__construct('app:return-coins');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $returnCoinCommand = ReturnCoinCommand::create(CoinStatusEnum::AVAILABLE);

            $returnCoinCommandHandler = new ReturnCoinCommandHandler($this->coinService);
            $output->write($returnCoinCommandHandler->handle($returnCoinCommand));

        } catch (Exception $ex) {
            $output->writeln($ex->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}

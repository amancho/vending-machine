<?php declare (strict_types=1);

namespace App\VendingMachine\Infrastructure\Console;

use App\VendingMachine\Application\Command\InsertCoinCommand;
use App\VendingMachine\Application\Command\InsertCoinCommandHandler;
use App\VendingMachine\Domain\Coin\CoinService;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InsertCoinConsoleCommand extends Command
{
    public function __construct(private readonly CoinService $coinService)
    {
        parent::__construct('app:insert-coin');
    }

    protected function configure(): void
    {
        $this->addArgument(
            'value',
            InputArgument::REQUIRED,
            'Insert coin'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $insertCoinCommand = InsertCoinCommand::create(
                (float) $input->getArgument('value')
            );

            $insertCoinCommandHandler = new InsertCoinCommandHandler($this->coinService);
            $output->writeln($insertCoinCommandHandler->handle($insertCoinCommand));

        } catch (Exception $ex) {
            $output->writeln($ex->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}

<?php declare (strict_types=1);

namespace App\VendingMachine\Infrastructure\Console;

use App\VendingMachine\Application\Command\ServiceAddCoinCommand;
use App\VendingMachine\Application\Command\ServiceAddCoinCommandHandler;
use App\VendingMachine\Domain\Coin\CoinService;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ServiceAddCoinConsoleCommand extends Command
{
    public function __construct(private readonly CoinService $coinService)
    {
        parent::__construct('app:service-add-coin');
    }

    protected function configure(): void
    {
        $this->addArgument(
            'value',
            InputArgument::REQUIRED,
            'Coin value'
        );

        $this->addArgument(
            'quantity',
            InputArgument::REQUIRED,
            'Coin quantity'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $serviceAddCoinCommand = ServiceAddCoinCommand::create(
                floatval($input->getArgument('value')),
                intval($input->getArgument('quantity'))
            );

            $serviceAddCoinCommandHandler = new ServiceAddCoinCommandHandler($this->coinService);
            $output->writeln($serviceAddCoinCommandHandler->handle($serviceAddCoinCommand));

        } catch (Exception $ex) {
            $output->writeln($ex->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}

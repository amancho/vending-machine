<?php declare (strict_types=1);

namespace App\VendingMachine\Infrastructure\Console;

use App\VendingMachine\Application\Query\Coin\GetCoins\GetCoins;
use App\VendingMachine\Application\Query\Coin\GetCoins\GetCoinsQuery;
use App\VendingMachine\Application\Query\Coin\GetCoins\GetCoinsResponse;
use App\VendingMachine\Domain\Coin\CoinRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ShowCoinsCommand extends Command
{
    protected static $defaultName = 'app:show-coins';

    public function __construct(private readonly CoinRepository $coinRepository)
    {
        parent::__construct(ShowCoinsCommand::$defaultName);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $getCoinsQuery = new GetCoins($this->coinRepository);

            /** @var $getCoinsResponse GetCoinsResponse */
            $getCoinsResponse = $getCoinsQuery->handle(new GetCoinsQuery());

            $output->write($getCoinsResponse->report());
        } catch (\Exception $ex) {
            $output->writeln($ex->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}

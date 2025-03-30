<?php declare (strict_types=1);

namespace App\VendingMachine\Infrastructure\Console;

use App\VendingMachine\Application\Command\GetProductCommand;
use App\VendingMachine\Application\Command\GetProductCommandHandler;
use App\VendingMachine\Application\Service\GetProductService;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetProductConsoleCommand extends Command
{
    public function __construct(private readonly GetProductService $getProductService)
    {
        parent::__construct('app:get-product');
    }

    protected function configure(): void
    {
        $this->addArgument(
            'product',
            InputArgument::REQUIRED,
            'Product name'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $getProductCommand = GetProductCommand::create(
                $input->getArgument('product')
            );

            $getProductCommandHandler = new GetProductCommandHandler($this->getProductService);
            $output->writeln($getProductCommandHandler->handle($getProductCommand));

        } catch (Exception $ex) {
            $output->writeln($ex->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}

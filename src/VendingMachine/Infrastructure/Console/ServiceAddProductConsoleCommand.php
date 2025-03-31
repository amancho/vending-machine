<?php declare (strict_types=1);

namespace App\VendingMachine\Infrastructure\Console;

use App\VendingMachine\Application\Command\ServiceAddProductCommand;
use App\VendingMachine\Application\Command\ServiceAddProductCommandHandler;
use App\VendingMachine\Domain\Product\ProductService;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ServiceAddProductConsoleCommand extends Command
{
    public function __construct(private readonly ProductService $productService)
    {
        parent::__construct('app:service-add-product');
    }

    protected function configure(): void
    {
        $this->addArgument(
            'product',
            InputArgument::REQUIRED,
            'Insert product name'
        );

        $this->addArgument(
            'quantity',
            InputArgument::REQUIRED,
            'Insert product quantity'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $serviceAddProductCommand = ServiceAddProductCommand::create(
                strval($input->getArgument('product')),
                intval($input->getArgument('quantity'))
            );

            $serviceAddProductCommandHandler = new ServiceAddProductCommandHandler($this->productService);
            $output->writeln($serviceAddProductCommandHandler->handle($serviceAddProductCommand));

        } catch (Exception $ex) {
            $output->writeln($ex->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}

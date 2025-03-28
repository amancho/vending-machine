<?php declare (strict_types=1);

namespace App\VendingMachine\Infrastructure\Console;

use App\VendingMachine\Application\Query\Product\GetProducts\GetProducts;
use App\VendingMachine\Application\Query\Product\GetProducts\GetProductsQuery;
use App\VendingMachine\Application\Query\Product\GetProducts\GetProductsResponse;
use App\VendingMachine\Domain\Product\ProductRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ShowProductsCommand extends Command
{
    protected static $defaultName = 'app:show-products';

    public function __construct(private readonly ProductRepository $productRepository)
    {
        parent::__construct(ShowProductsCommand::$defaultName);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $getProductsQuery = new GetProducts($this->productRepository);

            /** @var GetProductsResponse $getProductsResponse */
            $getProductsResponse = $getProductsQuery->handle(new GetProductsQuery());

            $output->write($getProductsResponse->report());
        } catch (\Exception $ex) {
            $output->writeln($ex->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}

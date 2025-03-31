<?php declare (strict_types=1);

namespace Integration\VendingMachine\Infrastructure\Console;

use App\VendingMachine\Application\Service\GetProductService;
use App\VendingMachine\Domain\Coin\CoinRepository;
use App\VendingMachine\Domain\Coin\CoinService;
use App\VendingMachine\Domain\Product\Enum\ProductsEnum;
use App\VendingMachine\Domain\Product\Product;
use App\VendingMachine\Domain\Product\ProductRepository;
use App\VendingMachine\Domain\Product\ProductService;
use App\VendingMachine\Infrastructure\Console\GetProductConsoleCommand;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use VendingMachine\Tests\Integration\IntegrationTestCase;

class GetProductConsoleCommandTest  extends IntegrationTestCase
{
    private Command $command;

    /** @var ProductRepository&MockObject  */
    private mixed $productRepository;

    /** @var CoinRepository&MockObject  */
    private mixed $coinRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productRepository = $this->createMock(ProductRepository::class);
        $this->coinRepository = $this->createMock(CoinRepository::class);

        $this->application->add(new GetProductConsoleCommand(
                new GetProductService(
                    new ProductService($this->productRepository),
                    new CoinService($this->coinRepository)
                )
            )
        );

        $this->command = $this->application->find('app:get-product');
    }

    public function test_get_product_works(): void
    {
        $this->productRepository
            ->expects(self::once())
            ->method('getByName')
            ->willReturn(Product::build(1, ProductsEnum::JUICE->value, 1, 1));

        $this->coinRepository
            ->expects(self::once())
            ->method('getByStatus')
            ->willReturn([['value' => 1.00, 'total' => 1]]);

        $commandTester = new CommandTester($this->command);
        $commandTester->execute(
            [
                'command'   => $this->command->getName(),
                'product'   => ProductsEnum::JUICE->value,
            ]
        );

        $output = $commandTester->getDisplay();
        $this->assertEquals('Get ' . ProductsEnum::JUICE->value .  PHP_EOL, $output);
    }

    public function test_get_product_throws_exception(): void
    {
        $this->productRepository
            ->expects(self::once())
            ->method('getByName')
            ->willThrowException(new Exception('Test exception'));

        $commandTester = new CommandTester($this->command);
        $commandTester->execute(
            [
                'command'   => $this->command->getName(),
                'product'   => ProductsEnum::JUICE->value,
            ]
        );

        $output = $commandTester->getDisplay();
        $this->assertEquals('Test exception' . PHP_EOL, $output);
    }
}

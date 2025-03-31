<?php declare (strict_types=1);

namespace Integration\VendingMachine\Infrastructure\Console;

use App\VendingMachine\Domain\Product\Enum\ProductsEnum;
use App\VendingMachine\Domain\Product\Errors\ProductIncorrectQuantity;
use App\VendingMachine\Domain\Product\Errors\ProductNotAllowed;
use App\VendingMachine\Domain\Product\ProductRepository;
use App\VendingMachine\Domain\Product\ProductService;
use App\VendingMachine\Infrastructure\Console\ServiceAddProductConsoleCommand;
use Exception;
use VendingMachine\Tests\Integration\IntegrationTestCase;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class ServiceAddProductConsoleCommandTest extends IntegrationTestCase
{
    /** @var ProductRepository&MockObject  */
    private mixed $repository;

    private Command $command;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createMock(ProductRepository::class);
        $this->application->add(new ServiceAddProductConsoleCommand(new ProductService($this->repository)));
        $this->command = $this->application->find('app:service-add-product');
    }

    public function test_service_add_product_works(): void
    {
        $this->repository
            ->expects(self::once())
            ->method('add');

        $commandTester = new CommandTester($this->command);
        $commandTester->execute(
            [
                'command'   => $this->command->getName(),
                'product'   => ProductsEnum::SODA->value,
                'quantity'  => 1,
            ]
        );

        $output = $commandTester->getDisplay();
        $this->assertEquals( 'Add soda 1 items' . PHP_EOL, $output);
    }

    public function test_service_add_product_name_fails(): void
    {
        $this->repository
            ->expects(self::never())
            ->method('add');

        $commandTester = new CommandTester($this->command);
        $commandTester->execute(
            [
                'command'   => $this->command->getName(),
                'product'   => 'test_product',
                'quantity'  => 1,
            ]
        );

        $output = $commandTester->getDisplay();
        $this->assertEquals((new ProductNotAllowed())->getMessage() . PHP_EOL, $output);
    }

    public function test_service_add_product_quantity_fails(): void
    {
        $this->repository
            ->expects(self::never())
            ->method('add');

        $commandTester = new CommandTester($this->command);
        $commandTester->execute(
            [
                'command'   => $this->command->getName(),
                'product'   => ProductsEnum::SODA->value,
                'quantity'  => 200,
            ]
        );

        $output = $commandTester->getDisplay();
        $this->assertEquals((new ProductIncorrectQuantity)->getMessage() . PHP_EOL, $output);
    }

    public function test_service_add_fails(): void
    {
        $this->repository
            ->expects(self::once())
            ->method('add')
            ->willThrowException(new Exception('Test exception'));

        $commandTester = new CommandTester($this->command);
        $commandTester->execute(
            [
                'command'   => $this->command->getName(),
                'product'   => ProductsEnum::SODA->value,
                'quantity'  => 1,
            ]
        );

        $output = $commandTester->getDisplay();
        $this->assertEquals('Test exception' . PHP_EOL, $output);
    }
}

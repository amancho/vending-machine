<?php declare (strict_types=1);

namespace Integration\VendingMachine\Infrastructure\Console;

use App\Shared\Domain\InvalidCollectionObjectException;
use App\VendingMachine\Domain\Product\Product;
use App\VendingMachine\Domain\Product\ProductCollection;
use App\VendingMachine\Domain\Product\ProductRepository;
use App\VendingMachine\Infrastructure\Console\ShowProductsCommand;

use stdClass;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

use VendingMachine\Tests\Integration\IntegrationTestCase;

class ShowProductsCommandTest extends IntegrationTestCase
{
    /** @var ProductRepository&MockObject  */
    private mixed $repository;
    private Command $command;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createMock(ProductRepository::class);
        $this->application->add(new ShowProductsCommand($this->repository));
        $this->command = $this->application->find('app:show-products');
    }

    /**
     * @throws InvalidCollectionObjectException
     */
    public function test_show_products_returns_the_expected_output(): void
    {
        $orderCollection = ProductCollection::create($this->getProducts());

        $this->repository
            ->expects(self::once())
            ->method('getAll')
            ->willReturn($orderCollection);

        $commandTester = new CommandTester($this->command);
        $commandTester->execute(['command'  => $this->command->getName()]);

        $expectedOutput = "Drink      | Price | Quantity 
----------------------------- 
juice      | 1     | 10
soda       | 1.5   | 10
water      | 0.65  | 10
";

        $output = $commandTester->getDisplay();
        $this->assertSame($expectedOutput, $output);
        $this->assertEquals(Command::SUCCESS, $commandTester->getStatusCode());
    }

    public function test_show_products_throws_exception(): void
    {
        $this->repository
            ->expects(self::once())
            ->method('getAll')
            ->willThrowException(new InvalidCollectionObjectException(new stdClass(), Product::class));

        $commandTester = new CommandTester($this->command);
        $commandTester->execute(['command'  => $this->command->getName()]);

        $this->assertEquals(Command::FAILURE, $commandTester->getStatusCode());
    }

    private function getProducts(): array
    {
        return [
            Product::build(1, 'Juice', 1.0, 10),
            Product::build(2, 'Soda', 1.50, 10),
            Product::build(3, 'Water', 0.65, 10),
        ];
    }
}

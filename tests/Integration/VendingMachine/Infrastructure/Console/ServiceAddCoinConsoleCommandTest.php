<?php declare (strict_types=1);

namespace Integration\VendingMachine\Infrastructure\Console;

use App\VendingMachine\Domain\Coin\CoinRepository;
use App\VendingMachine\Domain\Coin\CoinService;
use App\VendingMachine\Domain\Coin\Errors\CoinNotAllowed;
use App\VendingMachine\Domain\Product\ProductRepository;
use App\VendingMachine\Infrastructure\Console\ServiceAddCoinConsoleCommand;
use Exception;
use VendingMachine\Tests\Integration\IntegrationTestCase;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class ServiceAddCoinConsoleCommandTest extends IntegrationTestCase
{
    /** @var ProductRepository&MockObject  */
    private mixed $repository;

    private Command $command;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createMock(CoinRepository::class);
        $this->application->add(new ServiceAddCoinConsoleCommand(new CoinService($this->repository)));
        $this->command = $this->application->find('app:service-add-coin');
    }

    public function test_service_add_coin_works(): void
    {
        $this->repository
            ->expects(self::once())
            ->method('insert');

        $commandTester = new CommandTester($this->command);
        $commandTester->execute(
            [
                'command'   => $this->command->getName(),
                'value'     => 0.10,
                'quantity'  => 1,
            ]
        );

        $output = $commandTester->getDisplay();
        $this->assertEquals( 'Add 1 coins of 0.1' . PHP_EOL, $output);
    }

    public function test_service_add_not_allowed_coin_fails(): void
    {
        $this->repository
            ->expects(self::never())
            ->method('insert');

        $commandTester = new CommandTester($this->command);
        $commandTester->execute(
            [
                'command'   => $this->command->getName(),
                'value'     => 0.50,
                'quantity'  => 1,
            ]
        );

        $output = $commandTester->getDisplay();
        $this->assertEquals((new CoinNotAllowed())->getMessage() . PHP_EOL, $output);
    }

    public function test_service_add_coin_fails(): void
    {
        $this->repository
            ->expects(self::once())
            ->method('insert')
            ->willThrowException(new Exception('Test exception'));

        $commandTester = new CommandTester($this->command);
        $commandTester->execute(
            [
                'command'   => $this->command->getName(),
                'value'     => 0.10,
                'quantity'  => 1,
            ]
        );

        $output = $commandTester->getDisplay();
        $this->assertEquals('Test exception' . PHP_EOL, $output);
    }
}

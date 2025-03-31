<?php declare (strict_types=1);

namespace Integration\VendingMachine\Infrastructure\Console;

use App\VendingMachine\Domain\Coin\CoinRepository;
use App\VendingMachine\Domain\Coin\CoinService;
use App\VendingMachine\Domain\Coin\Enum\CoinStatusEnum;
use App\VendingMachine\Infrastructure\Console\ReturnCoinConsoleCommand;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use VendingMachine\Tests\Integration\IntegrationTestCase;

class ReturnCoinConsoleCommandTest  extends IntegrationTestCase
{
    /** @var CoinRepository&MockObject  */
    private mixed $repository;

    private Command $command;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createMock(CoinRepository::class);
        $this->application->add(new ReturnCoinConsoleCommand(new CoinService($this->repository)));
        $this->command = $this->application->find('app:return-coins');
    }

    public function test_return_coins_works(): void
    {
        $this->repository
            ->expects(self::once())
            ->method('getByStatus')
            ->with(CoinStatusEnum::AVAILABLE)
            ->willReturn([['value' => 0.05, 'total' => 1]]);

        $this->repository->expects(self::once())
            ->method('deleteByStatus');

        $commandTester = new CommandTester($this->command);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertEquals('Coins to return' . PHP_EOL  . '1 coins of 0.05' . PHP_EOL, $output);
    }

    public function test_return_coins_empty_works(): void
    {
        $this->repository
            ->expects(self::once())
            ->method('getByStatus')
            ->with(CoinStatusEnum::AVAILABLE)
            ->willReturn([]);

        $this->repository->expects(self::once())
            ->method('deleteByStatus');

        $commandTester = new CommandTester($this->command);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertEquals('You have no coins to return.', $output);
    }

    public function test_return_coins_throws_exception(): void
    {
        $this->repository
            ->expects(self::once())
            ->method('getByStatus')
            ->with(CoinStatusEnum::AVAILABLE)
            ->willThrowException(new Exception('Test exception'));

        $commandTester = new CommandTester($this->command);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertEquals('Test exception' . PHP_EOL, $output);
    }
}

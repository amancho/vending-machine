<?php declare (strict_types=1);

namespace Integration\VendingMachine\Infrastructure\Console;

use App\VendingMachine\Domain\Coin\CoinRepository;
use App\VendingMachine\Domain\Coin\Enum\CoinStatusEnum;
use App\VendingMachine\Infrastructure\Console\ReturnCoinConsoleCommand;
use Symfony\Component\Console\Tester\CommandTester;
use VendingMachine\Tests\Integration\IntegrationTestCase;

class ReturnCoinConsoleCommandTest  extends IntegrationTestCase
{
    /** @var CoinRepository&MockObject  */
    private mixed $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createMock(CoinRepository::class);
    }

    public function test_return_coins_expected_output(): void
    {
        $this->repository
            ->expects(self::once())
            ->method('getByStatus')
            ->with(CoinStatusEnum::AVAILABLE)
            ->willReturn([]);

        $this->repository->expects(self::once())
            ->method('deleteByStatus');

        $this->application->add(new ReturnCoinConsoleCommand($this->repository));
        $command = $this->application->find('app:return-coins');

        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertEquals('You have no coins to return.', $output);
    }
}

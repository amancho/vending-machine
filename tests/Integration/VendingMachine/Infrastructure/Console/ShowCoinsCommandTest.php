<?php declare (strict_types=1);

namespace Integration\VendingMachine\Infrastructure\Console;

use App\VendingMachine\Domain\Coin\CoinRepository;
use App\VendingMachine\Infrastructure\Console\ShowCoinsCommand;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

use VendingMachine\Tests\Integration\IntegrationTestCase;

class ShowCoinsCommandTest extends IntegrationTestCase
{
    /** @var CoinRepository&MockObject  */
    private mixed $repository;
    private Command $command;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createMock(CoinRepository::class);
        $this->application->add(new ShowCoinsCommand($this->repository));
        $this->command = $this->application->find('app:show-coins');
    }

    public function test_show_coins_returns_the_expected_output(): void
    {
        $coins= $this->getCoins();

        $this->repository
            ->expects(self::once())
            ->method('getByStatus')
            ->willReturn($coins);

        $commandTester = new CommandTester($this->command);
        $commandTester->execute(['command'  => $this->command->getName()]);

        $expectedOutput = "Coin     | Quantity | Amount
-----------------------------
0.1      | 2        | 0.2
0.25     | 2        | 0.5
";

        $output = $commandTester->getDisplay();
        $this->assertSame($expectedOutput, $output);
        $this->assertEquals(Command::SUCCESS, $commandTester->getStatusCode());
    }

    private function getCoins(): array
    {
        return [
            ['value' => 0.1, 'total' => 2],
            ['value' => 0.25, 'total' => 2],
        ];
    }
}

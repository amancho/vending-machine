<?php declare (strict_types=1);

namespace Integration\VendingMachine\Infrastructure\Console;

use App\VendingMachine\Domain\Coin\CoinRepository;
use App\VendingMachine\Infrastructure\Console\InsertCoinConsoleCommand;
use Symfony\Component\Console\Tester\CommandTester;
use VendingMachine\Tests\Integration\IntegrationTestCase;

class InsertCoinConsoleCommandTest  extends IntegrationTestCase
{
    /** @var CoinRepository&MockObject  */
    private mixed $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createMock(CoinRepository::class);
    }

    /**
     * @dataProvider valuesProvider
     * */
    public function test_insert_coins_returns_the_expected_output(float $value): void
    {
        $this->repository
            ->expects(self::once())
            ->method('insert');

        $this->application->add(new InsertCoinConsoleCommand($this->repository));
        $command = $this->application->find('app:insert-coin');

        $commandTester = new CommandTester($command);
        $commandTester->execute(
            [
                'command'   => $command->getName(),
                'value'     => $value,
            ]
        );

        $output = $commandTester->getDisplay();
        $this->assertEquals('You have insert a ' . $value . ' coin.' . PHP_EOL, $output);
    }

    public function valuesProvider(): array
    {
        return [
            ['value' => 0.05],
            ['value' => 0.10],
            ['value' => 0.25],
            ['value' => 1.00],
        ];
    }

    public function test_insert_coins_fails(): void
    {
        $this->repository
            ->expects(self::never())
            ->method('insert');

        $this->application->add(new InsertCoinConsoleCommand($this->repository));
        $command = $this->application->find('app:insert-coin');

        $commandTester = new CommandTester($command);
        $commandTester->execute(
            [
                'command'   => $command->getName(),
                'value'     => 0.50,
            ]
        );

        $output = $commandTester->getDisplay();
        $this->assertEquals('Coin not allowed, the coins allowed are [0.05, 0.10, 0.25, 1]' . PHP_EOL, $output);
    }
}
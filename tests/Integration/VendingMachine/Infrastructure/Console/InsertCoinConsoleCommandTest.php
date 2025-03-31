<?php declare (strict_types=1);

namespace Integration\VendingMachine\Infrastructure\Console;

use App\VendingMachine\Domain\Coin\CoinRepository;
use App\VendingMachine\Domain\Coin\CoinService;
use App\VendingMachine\Domain\Coin\Errors\CoinNotAllowed;
use App\VendingMachine\Infrastructure\Console\InsertCoinConsoleCommand;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use VendingMachine\Tests\Integration\IntegrationTestCase;

class InsertCoinConsoleCommandTest  extends IntegrationTestCase
{
    /** @var CoinRepository&MockObject  */
    private mixed $repository;

    private Command $command;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createMock(CoinRepository::class);
        $this->application->add(new InsertCoinConsoleCommand(new CoinService($this->repository)));
        $this->command = $this->application->find('app:insert-coin');
    }

    /**
     * @dataProvider valuesProvider
     * */
    public function test_insert_coins_returns_the_expected_output(float $value): void
    {
        $this->repository
            ->expects(self::once())
            ->method('insert');

        $commandTester = new CommandTester($this->command);
        $commandTester->execute(
            [
                'command'   => $this->command->getName(),
                'value'     => $value,
            ]
        );

        $output = $commandTester->getDisplay();
        $this->assertEquals('You have insert a ' . $value . ' coin.' . PHP_EOL, $output);
    }

    public function test_insert_coins_throws_exception(): void
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
            ]
        );

        $output = $commandTester->getDisplay();
        $this->assertEquals('Test exception' . PHP_EOL, $output);
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

        $commandTester = new CommandTester($this->command);
        $commandTester->execute(
            [
                'command'   => $this->command->getName(),
                'value'     => 0.50,
            ]
        );

        $output = $commandTester->getDisplay();
        $this->assertEquals((new CoinNotAllowed())->getMessage() . PHP_EOL, $output);
    }
}

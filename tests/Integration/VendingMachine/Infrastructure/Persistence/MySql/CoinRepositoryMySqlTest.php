<?php declare(strict_types=1);

namespace Integration\VendingMachine\Infrastructure\Persistence\MySql;

use App\VendingMachine\Domain\Coin\Coin;
use App\VendingMachine\Domain\Coin\CoinCollection;
use App\VendingMachine\Domain\Coin\Enum\CoinStatusEnum;
use App\VendingMachine\Infrastructure\Persistence\MySql\CoinRepositoryMySql;
use PDO;
use PDOStatement;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CoinRepositoryMySqlTest extends TestCase
{
    private CoinRepositoryMySql $repository;
    private MockObject&PDO $pdo;
    private MockObject&PDOStatement $statement;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->statement = $this->createMock(PDOStatement::class);

        $this->repository = new CoinRepositoryMySql($this->pdo);
    }

    public function test_it_gets_all_coins(): void
    {
        $coins = [
            ['id' => 1, 'amount' => 1.0, 'status' => CoinStatusEnum::AVAILABLE->value],
            ['id' => 2, 'amount' => 0.5, 'status' => CoinStatusEnum::AVAILABLE->value]
        ];

        $this->statement->method('fetchAll')->willReturn($coins);
        $this->pdo->method('query')->willReturn($this->statement);

        $result = $this->repository->getAll();

        $this->assertInstanceOf(CoinCollection::class, $result);
        $this->assertEquals(2, $result->count());
    }

    public function test_it_gets_coins_by_status(): void
    {
        $coins = [['value' => 1.0, 'total' => 2]];

        $this->statement->method('fetchAll')->willReturn($coins);
        $this->pdo->method('query')->willReturn($this->statement);

        $result = $this->repository->getByStatus(CoinStatusEnum::AVAILABLE);
        $this->assertSame($coins, $result);
    }

    public function test_it_inserts_a_coin(): void
    {
        $coin = Coin::build(1, 0.5, CoinStatusEnum::AVAILABLE->value);

        $this->pdo->expects($this->once())->method('prepare')->willReturn($this->statement);
        $this->statement->expects($this->once())->method('execute')->with([
            'value' => 0.5,
            'status' => CoinStatusEnum::AVAILABLE->value
        ]);

        $this->repository->insert($coin);
    }

    public function test_it_deletes_by_status(): void
    {
        $this->pdo->expects($this->once())->method('prepare')->willReturn($this->statement);
        $this->statement->expects($this->once())->method('execute')->with([
            'status' => CoinStatusEnum::AVAILABLE->value
        ]);

        $this->repository->deleteByStatus(CoinStatusEnum::AVAILABLE);
    }

    public function test_it_updates_status_by_value(): void
    {
        $this->pdo->expects($this->once())->method('prepare')->willReturn($this->statement);
        $this->statement->expects($this->once())->method('execute')->with([
            'value'         => 1.0,
            'status'        => CoinStatusEnum::AVAILABLE->value,
            'storedStatus'  => CoinStatusEnum::STORED->value
        ]);

        $this->repository->updateStatusByValue(1.0, CoinStatusEnum::AVAILABLE, 10);
    }
}

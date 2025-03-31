<?php declare(strict_types=1);

namespace VendingMachine\Tests\Unit\Domain\Coin;

use App\VendingMachine\Domain\Coin\Coin;
use App\VendingMachine\Domain\Coin\Enum\CoinStatusEnum;
use App\VendingMachine\Domain\Coin\ValueObject\CoinAmount;
use App\VendingMachine\Domain\Coin\ValueObject\CoinId;
use PHPUnit\Framework\TestCase;
use TypeError;

class CoinTest extends TestCase
{
    public function test_it_can_be_created(): void
    {
        $id = CoinId::build(1);
        $amount = CoinAmount::build(0.25);
        $status = CoinStatusEnum::AVAILABLE;

        $coin = Coin::create($id, $amount, $status);

        $this->assertInstanceOf(Coin::class, $coin);
        $this->assertSame($id, $coin->id());
        $this->assertSame($amount, $coin->amount());
        $this->assertSame($status, $coin->status());
    }

    public function test_it_can_be_built_from_primitive_values(): void
    {
        $coin = Coin::build(2, 1.00, CoinStatusEnum::AVAILABLE->value);

        $this->assertInstanceOf(Coin::class, $coin);
        $this->assertEquals(2, $coin->id()->value());
        $this->assertEquals(1.00, $coin->amount()->value());
        $this->assertSame(CoinStatusEnum::AVAILABLE, $coin->status());
    }

    public function test_build_throws_exception_for_invalid_status(): void
    {
        $this->expectException(TypeError::class);

        Coin::build(3, 0.50, 'INVALID_STATUS');
    }
}
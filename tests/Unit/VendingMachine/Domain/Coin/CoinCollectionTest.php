<?php declare(strict_types=1);

namespace VendingMachine\Tests\Unit\Domain\Coin;

use App\VendingMachine\Domain\Coin\Coin;
use App\VendingMachine\Domain\Coin\CoinCollection;
use App\Shared\Domain\InvalidCollectionObjectException;
use App\VendingMachine\Domain\Coin\Enum\CoinStatusEnum;
use PHPUnit\Framework\TestCase;
use stdClass;

class CoinCollectionTest extends TestCase
{
    public function test_it_can_be_initialized_empty(): void
    {
        $collection = CoinCollection::init();
        $this->assertInstanceOf(CoinCollection::class, $collection);
        $this->assertCount(0, $collection);
    }

    public function test_it_can_be_created_with_valid_coins(): void
    {
        $coins = [
            Coin::build(1, 0.25, CoinStatusEnum::AVAILABLE->value),
            Coin::build(2, 1.00, CoinStatusEnum::STORED->value),
            Coin::build(3, 0.10, CoinStatusEnum::RETURNED->value)
        ];

        $collection = CoinCollection::create($coins);

        $this->assertInstanceOf(CoinCollection::class, $collection);
        $this->assertCount(count($coins), $collection);
    }

    public function test_it_throws_exception_with_invalid_objects(): void
    {
        $this->expectException(InvalidCollectionObjectException::class);

        $invalidCoins = [new stdClass()];
        CoinCollection::create($invalidCoins);
    }
}

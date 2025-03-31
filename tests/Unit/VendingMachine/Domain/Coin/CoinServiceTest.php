<?php declare (strict_types=1);

namespace VendingMachine\Tests\Unit\Domain\Coin;

use App\VendingMachine\Domain\Coin\Coin;
use App\VendingMachine\Domain\Coin\CoinRepository;
use App\VendingMachine\Domain\Coin\CoinService;
use App\VendingMachine\Domain\Coin\Enum\CoinStatusEnum;
use App\VendingMachine\Domain\Coin\Errors\CoinNotAllowed;
use PHPUnit\Framework\TestCase;

class CoinServiceTest extends TestCase
{
    private CoinService $coinService;
    private CoinRepository $coinRepositoryMock;

    protected function setUp(): void
    {
        $this->coinRepositoryMock = $this->createMock(CoinRepository::class);
        $this->coinService = new CoinService($this->coinRepositoryMock);
    }

    public function test_insert_allowed_coin_works(): void
    {
        $coin = Coin::build(0, 0.10, CoinStatusEnum::AVAILABLE->value);

        $this->coinRepositoryMock
            ->expects($this->once())
            ->method('insert')
            ->with($coin);

        $this->coinService->insert($coin);
    }

    public function test_insert_not_allowed_coin_works(): void
    {
        $this->expectException(CoinNotAllowed::class);
        $this->coinService->insert(Coin::build(0, 0.50, CoinStatusEnum::AVAILABLE->value));
    }

    /**
     * @dataProvider getParams
     */
    public function test_can_give_change_works(float $change, $coins, $expected): void
    {
        $result = $this->coinService->canGiveChange($change, $coins);
        $this->assertEquals($expected, $result);
    }

    public function getParams(): array
    {
        return [
            [
                'change'    => 0.50,
                'coins'     => ['0.25' => 2],
                'expected'  => true,
            ],
            [
                'change'    => 0.15,
                'coins'     => ['0.05' => 1, '0.10' => 2],
                'expected'  => true,
            ],
            [
                'change'    => 0.50,
                'coins'     => ['0.05' => 2, '0.10' => 4],
                'expected'  => true,
            ],
            [
                'change'    => 0.05,
                'coins'     => ['0.10' => 1, '0.25' => 2],
                'expected'  => false,
            ],
            [
                'change'    => 0.10,
                'coins'     => ['0.25' => 1],
                'expected'  => false,
            ],
        ];
    }

    public function test_get_coins_works(): void
    {
        $coins = [
            ['value' => 0.05, 'total' => 1],
            ['value' => 0.10, 'total' => 2],
            ['value' => 0.25, 'total' => 3],
            ['value' => 1,    'total' => 4]
        ];

        $expected = ['0.05' => 1, '0.10' => 2, '0.25' => 3, '1.00' => 4];

        $result = $this->coinService->getCoins($coins);
        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider getCoins
     */
    public function test_calculate_coins_change_works(float $change, $coins, $expected): void
    {
        $result = $this->coinService->calculateCoinsChange($change, $coins);
        $this->assertEquals($expected, $result);
    }

    public function getCoins(): array
    {
        return [
            [
                'change'    => 0.50,
                'coins'     => ['0.25' => 2],
                'expected'  => ['0.25' => 2],
            ],
            [
                'change'    => 0.50,
                'coins'     => ['1.00' => 1, '0.10' => 6],
                'expected'  => ['0.10' => 5],
            ],
            [
                'change'    => 0.35,
                'coins'     => ['0.25' => 2, '0.05' => 3],
                'expected'  => ['0.25' => 1, '0.05' => 2],
            ],
        ];
    }

    public function test_get_available_coins_works(): void
    {
        $this->coinRepositoryMock
            ->expects($this->once())
            ->method('getByStatus')
            ->willReturn([
                ['value' => 0.05, 'total' => 1],
                ['value' => 0.10, 'total' => 2]
            ]);

        $expected = ['0.05' => 1, '0.10' => 2,];

        $result = $this->coinService->getAvailableCoins();
        $this->assertEquals($expected, $result);
    }

    public function test_get_obtainable_coins_works(): void
    {
        $this->coinRepositoryMock
            ->expects($this->once())
            ->method('getByMultipleStatus')
            ->willReturn([
                ['value' => 0.25, 'total' => 3],
                ['value' => 1,    'total' => 4]
            ]);

        $expected = ['0.25' => 3, '1.00' => 4];

        $result = $this->coinService->getObtainableCoins();
        $this->assertEquals($expected, $result);
    }

    public function test_get_amount_works(): void
    {
        $coins = [
            '0.25' => 3,
            '1.00' => 4
        ];

        $result = $this->coinService->getAmount($coins);
        $this->assertEquals(4.75, $result);
    }

    public function test_return_change_works(): void
    {
        $this->coinRepositoryMock
            ->expects($this->exactly(2))
            ->method('updateStatusByValue');

        $coins = [
            '0.25' => 3,
            '1.00' => 4
        ];

        $this->coinService->returnChange($coins);
    }
}

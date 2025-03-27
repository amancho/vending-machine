<?php declare (strict_types=1);

namespace Unit\VendingMachine\Domain\Coin;

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

    public function test_insert_allowed_coin(): void
    {
        $this->coinRepositoryMock
            ->expects($this->once())
            ->method('insert')
            ->with(0.10);

        $message = $this->coinService->insert(0.10);
        $this->assertEquals('You have insert a 0.1 coin.', $message);
    }

    public function test_insert_not_allowed_coin(): void
    {
        $this->expectException(CoinNotAllowed::class);
        $this->coinService->insert(0.07);
    }

    /**
     * @dataProvider getParams
     */
    public function test_can_give_change(float $change, $coins, $expected): void
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

    public function test_get_coins(): void
    {
        $this->coinRepositoryMock
            ->method('getByStatus')
            ->willReturn([
                ['value' => 0.10, 'total' => 5],
                ['value' => 0.25, 'total' => 3],
            ]);

        $result = $this->coinService->getCoins(CoinStatusEnum::STORED);
        $this->assertEquals(['0.10' => 5, '0.25' => 3], $result);
    }
}

<?php declare (strict_types=1);

namespace App\VendingMachine\Domain\Coin;

use App\VendingMachine\Domain\Coin\Enum\CoinStatusEnum;
use App\VendingMachine\Domain\Coin\ValueObject\CoinAmount;
use App\VendingMachine\Domain\Coin\ValueObject\CoinId;

readonly class Coin
{
    public function __construct(
        private CoinId          $id,
        private CoinAmount      $amount,
        private CoinStatusEnum  $status,
    )
    {
    }

    /**
     * @param CoinId $id
     * @param CoinAmount $amount
     * @param CoinStatusEnum $status
     * @return Coin
     */
    public static function create(
        CoinId          $id,
        CoinAmount      $amount,
        CoinStatusEnum  $status,
    ): Coin {
        return new self($id, $amount, $status);
    }

    public static function build(int $id, float $amount, string $status): Coin
    {
        return self::create(
            CoinId::build($id),
            CoinAmount::build($amount),
            CoinStatusEnum::tryFrom($status),
        );
    }

    public function id(): CoinId
    {
        return $this->id;
    }

    public function amount(): CoinAmount
    {
        return $this->amount;
    }

    public function status(): CoinStatusEnum
    {
        return $this->status;
    }
}

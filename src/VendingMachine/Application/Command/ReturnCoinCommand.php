<?php declare (strict_types=1);

namespace App\VendingMachine\Application\Command;

use App\Shared\Domain\Bus\Command\Command;
use App\VendingMachine\Domain\Coin\Enum\CoinStatusEnum;

readonly class ReturnCoinCommand implements Command
{
    public function __construct(private CoinStatusEnum $status)
    {
    }

    public static function create(CoinStatusEnum $status): ReturnCoinCommand
    {
        return new self($status);
    }

    public function status(): CoinStatusEnum
    {
        return $this->status;
    }
}

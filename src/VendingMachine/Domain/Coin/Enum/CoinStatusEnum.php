<?php declare (strict_types=1);

namespace App\VendingMachine\Domain\Coin\Enum;

enum CoinStatusEnum: string
{
    case AVAILABLE = 'available';
    case STORED = 'stored';
}

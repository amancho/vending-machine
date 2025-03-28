<?php declare (strict_types=1);

namespace App\VendingMachine\Domain\Coin\Errors;

use App\Shared\Domain\DomainError;

final class CoinNotAllowed extends DomainError
{
    public function __construct()
    {
        parent::__construct();
    }

    public function errorCode(): string
    {
        return 'COIN_NOT_ALLOWED';
    }

    protected function errorMessage(): string
    {
        return 'Coin not allowed. The allowed coins are [0.05, 0.10, 0.25, 1]';
    }
}

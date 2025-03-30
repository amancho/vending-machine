<?php declare (strict_types=1);

namespace App\VendingMachine\Domain\Coin\Errors;

use App\Shared\Domain\DomainError;

final class CoinsChangeNotAvailable extends DomainError
{
    public function __construct()
    {
        parent::__construct();
    }

    public function errorCode(): string
    {
        return 'COIN_CHANGE_NOT_AVAILABLE';
    }

    protected function errorMessage(): string
    {
        return 'Change not available';
    }
}

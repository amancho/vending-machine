<?php declare (strict_types=1);

namespace App\VendingMachine\Domain\Coin\Errors;

use App\Shared\Domain\DomainError;

final class CoinsInsuficientAmount extends DomainError
{
    public function __construct(private readonly float $amount)
    {
        parent::__construct();
    }

    public function errorCode(): string
    {
        return 'COIN_INSUFFICIENT_AMOUNT';
    }

    protected function errorMessage(): string
    {
        return sprintf('Insufficient amount(%s). Please, insert coins.', $this->amount);
    }
}

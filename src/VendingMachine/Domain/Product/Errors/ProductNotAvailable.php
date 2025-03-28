<?php declare (strict_types=1);

namespace App\VendingMachine\Domain\Product\Errors;

use App\Shared\Domain\DomainError;

final class ProductNotAvailable extends DomainError
{
    public function __construct()
    {
        parent::__construct();
    }

    public function errorCode(): string
    {
        return 'PRODUCT_NOT_AVAILABLE';
    }

    protected function errorMessage(): string
    {
        return 'Product not available.';
    }
}

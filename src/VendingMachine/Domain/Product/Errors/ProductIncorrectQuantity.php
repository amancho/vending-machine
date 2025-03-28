<?php declare (strict_types=1);

namespace App\VendingMachine\Domain\Product\Errors;

use App\Shared\Domain\DomainError;

final class ProductIncorrectQuantity extends DomainError
{
    public function __construct()
    {
        parent::__construct();
    }

    public function errorCode(): string
    {
        return 'PRODUCT_INCORRECT_QUANTITY';
    }

    protected function errorMessage(): string
    {
        return 'Product quantity must be greater than 0 and less than 100.';
    }
}

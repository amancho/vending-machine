<?php declare (strict_types=1);

namespace App\VendingMachine\Domain\Product\Enum;

enum ProductsEnum: string
{
    case SODA = 'soda';
    case JUICE = 'juice';
    case WATER = 'water';
}

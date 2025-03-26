<?php declare (strict_types=1);

namespace App\VendingMachine\Domain\Product;

use App\Shared\Domain\Collection;
use App\Shared\Domain\InvalidCollectionObjectException;

class ProductCollection extends Collection
{
    protected function type(): string
    {
        return Product::class;
    }

    /**
     * @throws InvalidCollectionObjectException
     */
    public static function init(): self
    {
        return new ProductCollection([]);
    }

    /**
     * @throws InvalidCollectionObjectException
     */
    public static function create(array $products): self
    {
        return new self($products);
    }
}

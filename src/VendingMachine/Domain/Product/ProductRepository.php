<?php declare (strict_types=1);

namespace App\VendingMachine\Domain\Product;

use App\Shared\Domain\InvalidCollectionObjectException;

interface ProductRepository
{
    /**
     * @throws InvalidCollectionObjectException
     */
    public function getAll(): ProductCollection;

    public function add(string $name, int $quantity): void;
}
<?php declare (strict_types=1);

namespace App\VendingMachine\Domain\Product;

use App\VendingMachine\Domain\Product\Enum\ProductsEnum;
use App\VendingMachine\Domain\Product\Errors\ProductIncorrectQuantity;
use App\VendingMachine\Domain\Product\Errors\ProductNotAllowed;

readonly class ProductService
{
    public function __construct(private ProductRepository $productRepository)
    {
    }

    public function add(string $name, int $quantity): void
    {
        $product = ProductsEnum::tryFrom($name);
        if ($product === null) {
            throw new ProductNotAllowed();
        }

        $this->checkQuantity($quantity);

        $this->productRepository->add($name, $quantity);
    }

    private function checkQuantity(int $quantity): void
    {
        if ($quantity <= 0 || $quantity > 100) {
            throw new ProductIncorrectQuantity();
        }
    }
}

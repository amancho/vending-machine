<?php declare (strict_types=1);

namespace Unit\VendingMachine\Domain\Product;

use App\VendingMachine\Domain\Product\Errors\ProductIncorrectQuantity;
use App\VendingMachine\Domain\Product\Errors\ProductNotAllowed;
use App\VendingMachine\Domain\Product\ProductRepository;
use App\VendingMachine\Domain\Product\ProductService;
use PHPUnit\Framework\TestCase;

class ProductServiceTest extends TestCase
{
    private ProductService $productService;
    private ProductRepository $productRepositoryMock;

    protected function setUp(): void
    {
        $this->productRepositoryMock = $this->createMock(ProductRepository::class);
        $this->productService = new ProductService($this->productRepositoryMock);
    }

    public function test_add_incorrect_product_fails(): void
    {
        $this->expectException(ProductNotAllowed::class);
        $this->productService->add('test_product', 0);
    }

    public function test_add_incorrect_quantity_fails(): void
    {
        $this->expectException(ProductIncorrectQuantity::class);
        $this->productService->add('soda', 0);
    }
}

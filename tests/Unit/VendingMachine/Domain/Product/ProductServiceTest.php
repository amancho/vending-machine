<?php declare (strict_types=1);

namespace Unit\VendingMachine\Domain\Product;

use App\VendingMachine\Domain\Product\Errors\ProductIncorrectQuantity;
use App\VendingMachine\Domain\Product\Errors\ProductNotAllowed;
use App\VendingMachine\Domain\Product\Errors\ProductNotAvailable;
use App\VendingMachine\Domain\Product\Product;
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

    public function test_decrease_quantity_fails(): void
    {
        $this->expectException(ProductNotAvailable::class);

        $product = Product::build(0, 'test_product', 1, 0);
        $this->productService->decreaseQuantity($product);
    }

    public function test_get_product_fails(): void
    {
        $this->expectException(ProductNotAllowed::class);
        $this->productService->get('test_product');
    }
}

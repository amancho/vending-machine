<?php declare(strict_types=1);

namespace VendingMachine\Tests\Unit\Domain\Product;

use App\Shared\Domain\InvalidCollectionObjectException;
use App\VendingMachine\Domain\Product\Enum\ProductsEnum;
use App\VendingMachine\Domain\Product\Product;
use App\VendingMachine\Domain\Product\ProductCollection;
use PHPUnit\Framework\TestCase;
use stdClass;

class ProductCollectionTest extends TestCase
{
    public function test_it_can_be_initialized_empty(): void
    {
        $collection = ProductCollection::init();
        $this->assertInstanceOf(ProductCollection::class, $collection);
        $this->assertCount(0, $collection);
    }

    public function test_it_can_be_created_with_valid_coins(): void
    {
        $products = [
            Product::build(1, ProductsEnum::SODA->value, 1.50, 1),
            Product::build(2, ProductsEnum::JUICE->value, 1.00, 1),
            Product::build(3, ProductsEnum::WATER->value, 0.65, 1)
        ];

        $collection = ProductCollection::create($products);

        $this->assertInstanceOf(ProductCollection::class, $collection);
        $this->assertCount(count($products), $collection);
    }

    public function test_it_throws_exception_with_invalid_objects(): void
    {
        $this->expectException(InvalidCollectionObjectException::class);

        $invalidCoins = [new stdClass()];
        ProductCollection::create($invalidCoins);
    }
}

<?php declare(strict_types=1);

namespace VendingMachine\Tests\Unit\Domain\Product;

use App\VendingMachine\Domain\Product\Enum\ProductsEnum;
use App\VendingMachine\Domain\Product\Product;
use App\VendingMachine\Domain\Product\ValueObject\ProductId;
use App\VendingMachine\Domain\Product\ValueObject\ProductName;
use App\VendingMachine\Domain\Product\ValueObject\ProductPrice;
use App\VendingMachine\Domain\Product\ValueObject\ProductQuantity;
use PHPUnit\Framework\TestCase;
use ValueError;

class ProductTest extends TestCase
{
    public function test_it_can_be_created(): void
    {
        $id = ProductId::build(1);
        $name = ProductName::build(ProductsEnum::SODA->value);
        $price = ProductPrice::build(1.50);
        $quantity = ProductQuantity::build(10);

        $product = Product::create($id, $name, $price, $quantity);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertSame($id, $product->id());
        $this->assertSame($name, $product->name());
        $this->assertSame($price, $product->price());
        $this->assertSame($quantity, $product->quantity());
    }

    public function test_it_can_be_built_from_primitive_values(): void
    {
        $product = Product::build(2, ProductsEnum::SODA->value, 1.50, 5);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals(2, $product->id()->value());
        $this->assertEquals(ProductsEnum::SODA->value, $product->name()->value());
        $this->assertEquals(1.50, $product->price()->value());
        $this->assertEquals(5, $product->quantity()->value());
    }

    public function test_it_can_be_built_from_array(): void
    {
        $data = [
            'id'        => 3,
            'name'      => ProductsEnum::JUICE->value,
            'price'     => 1.00,
            'quantity'  => 20
        ];

        $product = Product::buildFromArray($data);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals(3, $product->id()->value());
        $this->assertEquals(ProductsEnum::JUICE->value, $product->name()->value());
        $this->assertEquals(1.00, $product->price()->value());
        $this->assertEquals(20, $product->quantity()->value());
    }

    public function test_to_array_returns_correct_values(): void
    {
        $product = Product::build(4, ProductsEnum::WATER->value, 0.65, 15);
        $expectedArray = [
            'name'      => ProductsEnum::WATER->value,
            'price'     => 0.65,
            'quantity'  => 15,
        ];

        $this->assertEquals($expectedArray, $product->toArray());
    }

    public function test_build_throws_exception_for_invalid_values(): void
    {
        $this->expectException(ValueError::class);

        Product::build(5, 'Invalid', -1.50, -10);
    }
}

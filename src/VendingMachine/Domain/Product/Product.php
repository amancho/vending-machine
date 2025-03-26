<?php declare (strict_types=1);

namespace App\VendingMachine\Domain\Product;

use App\VendingMachine\Domain\Product\ValueObject\ProductId;
use App\VendingMachine\Domain\Product\ValueObject\ProductName;
use App\VendingMachine\Domain\Product\ValueObject\ProductPrice;
use App\VendingMachine\Domain\Product\ValueObject\ProductQuantity;

class Product
{
    public function __construct(
        private readonly ProductId $id,
        private readonly ProductName $name,
        private readonly ProductPrice $price,
        private readonly ProductQuantity $quantity
    )
    {
    }

    /**
     * @param ProductId $id
     * @param ProductName $name
     * @param ProductPrice $price
     * @param ProductQuantity $quantity
     * @return Product
     */
    public static function create(
        ProductId $id,
        ProductName $name,
        ProductPrice $price,
        ProductQuantity $quantity,
    ): Product {
        return new self($id, $name, $price, $quantity);
    }

    public static function build(int $id, string $name, float $price, int $quantity): Product
    {
        return self::create(
            ProductId::build($id),
            ProductName::build($name),
            ProductPrice::build($price),
            ProductQuantity::build($quantity)
        );
    }

    public function id(): ProductId
    {
        return $this->id;
    }

    public function name(): ProductName
    {
        return $this->name;
    }

    public function price(): ProductPrice
    {
        return $this->price;
    }

    public function quantity(): ProductQuantity
    {
        return $this->quantity;
    }
}
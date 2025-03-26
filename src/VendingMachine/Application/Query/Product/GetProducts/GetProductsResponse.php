<?php declare (strict_types=1);

namespace App\VendingMachine\Application\Query\Product\GetProducts;

use App\Shared\Domain\Bus\Query\QueryResponse;
use App\VendingMachine\Domain\Product\Product;
use App\VendingMachine\Domain\Product\ProductCollection;

final readonly class GetProductsResponse implements QueryResponse
{
    public function __construct(private ProductCollection $productCollection)
    {
    }

    public static function build(ProductCollection $productCollection): GetProductsResponse
    {
        return new self($productCollection);
    }

    public function toArray(): array
    {
        $products = [];

        /** @var Product $product */
        foreach ($this->productCollection as $product) {
            $products[] = $product->toArray();
        }

        return $products;
    }

    public function report(): string
    {
        $report = 'Drink      | Price | Quantity ' . PHP_EOL;
        $report .= '----------------------------- ' . PHP_EOL;

        /** @var Product $product */
        foreach ($this->productCollection as $product) {
            $report .= $this->format($product->name()->value(), 10)
                . ' | ' . $this->format(strval($product->price()->value()), 5)
                . ' | ' . $product->quantity()->value() . PHP_EOL ;
        }

        return $report;
    }

    private function format(string $type, int $length = 7): string
    {
        return substr($type . '        ', 0, $length);
    }
}

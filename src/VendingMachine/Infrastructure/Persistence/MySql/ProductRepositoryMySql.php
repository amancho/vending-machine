<?php declare (strict_types=1);

namespace App\VendingMachine\Infrastructure\Persistence\MySql;

use App\Shared\Domain\InvalidCollectionObjectException;
use App\Shared\Infrastructure\Persistence\MySql\MySqlRepository;
use App\VendingMachine\Domain\Product\Errors\ProductNotFound;
use App\VendingMachine\Domain\Product\Product;
use App\VendingMachine\Domain\Product\ProductCollection;
use App\VendingMachine\Domain\Product\ProductRepository;
use PDO;

final class ProductRepositoryMySql implements ProductRepository
{
    private PDO $client;

    public function __construct(PDO $client)
    {
        $this->client = $client;
    }

    public static function build(): ProductRepository
    {
        return new self(MySqlRepository::getClient());
    }

    /**
     * @throws InvalidCollectionObjectException
     */
    public function getAll(): ProductCollection
    {
        $query = $this->client->query('SELECT * FROM products');
        $products = $query->fetchAll();

        $productCollection = ProductCollection::init();

        if (!empty($products)) {
            foreach ($products as $product) {
                $productCollection->add($this->toDomain($product));
            }
        }

        return $productCollection;
    }

    /**
     * @throws ProductNotFound
     */
    public function getByName(string $name): Product
    {
        $sql = sprintf("SELECT * FROM products WHERE name = '%s' LIMIT 1", $name);
        $query = $this->client->query($sql);
        $product = $query->fetch();

        if (empty($product)) {
            throw new ProductNotFound();
        }

        return Product::buildFromArray($product);
    }

    public function add(string $name, int $quantity): void
    {
        $stmt = $this->client->prepare('UPDATE products SET quantity = (quantity + :quantity) WHERE name = :name');
        $stmt->execute([
            'quantity'  => $quantity,
            'name'      => $name,
        ]);
    }

    public function decrease(Product $product): void
    {
        $stmt = $this->client->prepare('UPDATE products SET quantity = (quantity - 1) WHERE id = :id');
        $stmt->execute([
            'id'  => $product->id(),
        ]);
    }

    private function toInfrastructure(Product $product): array
    {
        return [
            'name' => $product->name()->value(),
            'price' => $product->price()->value(),
            'quantity' => $product->quantity()->value(),
        ];
    }

    private function toDomain(array $product): Product
    {
        return Product::build(
            $product['id'],
            $product['name'],
            (float) $product['price'],
            $product['quantity'],
        );
    }
}

<?php declare(strict_types=1);

namespace Integration\VendingMachine\Infrastructure\Persistence\MySql;

use App\VendingMachine\Domain\Product\Enum\ProductsEnum;
use App\VendingMachine\Domain\Product\Errors\ProductNotFound;
use App\VendingMachine\Domain\Product\Product;
use App\VendingMachine\Domain\Product\ProductCollection;
use App\VendingMachine\Infrastructure\Persistence\MySql\ProductRepositoryMySql;
use PDO;
use PDOStatement;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductRepositoryMySqlTest extends TestCase
{
    private ProductRepositoryMySql $repository;
    private MockObject&PDO $pdo;
    private MockObject&PDOStatement $statement;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->statement = $this->createMock(PDOStatement::class);

        $this->repository = new ProductRepositoryMySql($this->pdo);
    }

    public function test_it_gets_all_products(): void
    {
        $products = [
            ['id' => 1, 'name' => ProductsEnum::SODA->value, 'price' => 1.5, 'quantity' => 10],
            ['id' => 2, 'name' => ProductsEnum::JUICE->value, 'price' => 1.0, 'quantity' => 5]
        ];

        $this->statement->method('fetchAll')->willReturn($products);
        $this->pdo->method('query')->willReturn($this->statement);

        $result = $this->repository->getAll();

        $this->assertInstanceOf(ProductCollection::class, $result);
        $this->assertEquals(2, $result->count());
    }

    public function test_it_gets_product_by_name(): void
    {
        $product = ['id' => 1, 'name' => ProductsEnum::SODA->value, 'price' => 1.5, 'quantity' => 10];

        $this->statement->method('fetch')->willReturn($product);
        $this->pdo->method('query')->willReturn($this->statement);

        $result = $this->repository->getByName(ProductsEnum::SODA->value);

        $this->assertInstanceOf(Product::class, $result);
        $this->assertSame(ProductsEnum::SODA->value, $result->name()->value());
        $this->assertSame(1.5, $result->price()->value());
        $this->assertSame(10, $result->quantity()->value());
    }

    public function test_it_throws_exception_if_product_not_found(): void
    {
        $this->statement->method('fetch')->willReturn(false);
        $this->pdo->method('query')->willReturn($this->statement);

        $this->expectException(ProductNotFound::class);
        $this->repository->getByName('UnknownProduct');
    }

    public function test_it_adds_product_quantity(): void
    {
        $this->pdo->expects($this->once())->method('prepare')->willReturn($this->statement);
        $this->statement->expects($this->once())->method('execute')->with([
            'quantity'  => 5,
            'name'      => ProductsEnum::SODA->value
        ]);

        $this->repository->add(ProductsEnum::SODA->value, 5);
    }

    public function test_it_decreases_product_quantity(): void
    {
        $product = Product::build(1, ProductsEnum::SODA->value, 1.5, 10);

        $this->pdo->expects($this->once())->method('prepare')->willReturn($this->statement);
        $this->statement->expects($this->once())->method('execute')->with([
            'id' => 1
        ]);

        $this->repository->decrease($product);
    }
}

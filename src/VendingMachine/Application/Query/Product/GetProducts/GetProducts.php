<?php declare (strict_types=1);

namespace App\VendingMachine\Application\Query\Product\GetProducts;

use App\VendingMachine\Domain\Product\ProductRepository;
use App\Shared\Domain\Bus\Query\Query;
use App\Shared\Domain\Bus\Query\QueryHandler;
use App\Shared\Domain\Bus\Query\QueryResponse;
use App\Shared\Domain\InvalidCollectionObjectException;

final class GetProducts extends QueryHandler
{
    public function __construct(private readonly ProductRepository $productRepository)
    {
    }

    /**
     * @param GetProductsQuery $query
     * @throws InvalidCollectionObjectException
     */
    public function handle(Query $query): QueryResponse
    {
        return GetProductsResponse::build($this->productRepository->getAll());
    }
}

<?php declare (strict_types=1);

namespace App\VendingMachine\Application\Query\Coin\GetCoins;

use App\VendingMachine\Domain\Coin\CoinRepository;
use App\Shared\Domain\Bus\Query\Query;
use App\Shared\Domain\Bus\Query\QueryHandler;
use App\Shared\Domain\Bus\Query\QueryResponse;
use App\VendingMachine\Domain\Coin\Enum\CoinStatusEnum;

final class GetCoins extends QueryHandler
{
    public function __construct(private readonly CoinRepository $coinRepository)
    {
    }

    /**
     * @param GetCoinsQuery $query
     */
    public function handle(Query $query): QueryResponse
    {
        return GetCoinsResponse::build($this->coinRepository->getByStatus(CoinStatusEnum::STORED));
    }
}

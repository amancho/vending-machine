<?php declare (strict_types=1);

namespace App\VendingMachine\Infrastructure\Persistence\MySql;

use App\Shared\Domain\InvalidCollectionObjectException;
use App\Shared\Infrastructure\Persistence\MySql\MySqlRepository;
use App\VendingMachine\Domain\Coin\Coin;
use App\VendingMachine\Domain\Coin\CoinCollection;
use App\VendingMachine\Domain\Coin\CoinRepository;
use App\VendingMachine\Domain\Coin\Enum\CoinStatusEnum;
use PDO;

final class CoinRepositoryMySql implements CoinRepository
{
    private PDO $client;

    public function __construct(PDO $client)
    {
        $this->client = $client;
    }

    public static function build(): CoinRepository
    {
        return new self(MySqlRepository::getClient());
    }

    /**
     * @throws InvalidCollectionObjectException
     */
    public function getAll(): CoinCollection
    {
        $query = $this->client->query("SELECT * FROM coins");
        $coins = $query->fetchAll();

        $coinsCollection = CoinCollection::init();

        if (!empty($coins)) {
            foreach ($coins as $coin) {
                $coinsCollection->add($this->toDomain($coin));
            }
        }

        return $coinsCollection;
    }

    public function getByStatus(CoinStatusEnum $status): array
    {
        $sql = sprintf("SELECT value, count(value) as total FROM coins WHERE status = '%s' GROUP BY value", $status->value);
        $query = $this->client->query($sql);

        return $query->fetchAll();
    }

    private function toDomain(array $coin): Coin
    {
        return Coin::build(
            $coin['id'],
            floatval($coin['amount']),
            strval($coin['status']),
        );
    }
}

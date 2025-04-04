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

    public function getByMultipleStatus(string $status): array
    {
        $sql = "SELECT value, COUNT(*) as total FROM coins 
                    WHERE status IN ($status) 
                    GROUP BY value 
                    ORDER BY value DESC";

        $query = $this->client->query($sql);

        return $query->fetchAll();
    }

    public function getByStatus(CoinStatusEnum $status): array
    {
        $sql = sprintf("SELECT value, count(value) as total FROM coins WHERE status = '%s' GROUP BY value", $status->value);
        $query = $this->client->query($sql);

        return $query->fetchAll();
    }

    public function insert(Coin $coin): void
    {
        $stmt = $this->client->prepare('INSERT INTO coins (value, status) VALUES (:value, :status)');
        $stmt->execute([
            'value'     => $coin->amount()->value(),
            'status'    => $coin->status()->value,
        ]);
    }

    public function deleteByStatus(CoinStatusEnum $status): void
    {
        $stmt = $this->client->prepare('DELETE FROM coins WHERE status = :status');
        $stmt->execute(['status' => $status->value]);
    }

    public function storeByStatus(CoinStatusEnum $status): void
    {
        $stmt = $this->client->prepare("UPDATE coins SET status = :stored WHERE status = :status");

        $stmt->execute([
            'stored'  => CoinStatusEnum::STORED->value,
            'status' => CoinStatusEnum::AVAILABLE->value
        ]);
    }

    public function updateStatusByValue(float $value, CoinStatusEnum $status, int $limit): void
    {
        $sql = "UPDATE coins 
            SET status = :status 
            WHERE value = :value 
            AND status = :storedStatus 
            LIMIT $limit";

        $stmt = $this->client->prepare($sql);
        $stmt->execute([
            'value'         => $value,
            'status'        => $status->value,
            'storedStatus'  => CoinStatusEnum::STORED->value,
        ]);
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

<?php declare (strict_types=1);

namespace App\VendingMachine\Application\Service;

use App\VendingMachine\Domain\Coin\CoinService;
use App\VendingMachine\Domain\Coin\Enum\CoinStatusEnum;
use App\VendingMachine\Domain\Coin\Errors\CoinsChangeNotAvailable;
use App\VendingMachine\Domain\Product\Errors\ProductNotAvailable;
use App\VendingMachine\Domain\Product\Product;
use App\VendingMachine\Domain\Product\ProductService;

readonly class GetProductService
{
    public function __construct(
        private ProductService $productService,
        private CoinService    $coinService
    ) {}

    public function execute(string $product): void
    {
        $product = $this->productService->get($product);
        if ($product->quantity()->value() == 0) {
            throw new ProductNotAvailable();
        }

        $change = $this->calculateChange($product);
        $coinsToReturn = $this->coinService->calculateCoinsChange($change);

        $this->coinService->store();
        $this->coinService->returnChange($coinsToReturn);

        $this->productService->decreaseQuantity($product);
    }

    private function calculateChange(Product $product): float
    {
        $availableCoins = $this->coinService->getCoins(CoinStatusEnum::AVAILABLE);
        $availableAmount = $this->coinService->getAvailableAmount($availableCoins);

        $change = $product->price()->value() - $availableAmount;
        if (!$this->coinService->canGiveChange($change, $availableCoins)) {
            throw new CoinsChangeNotAvailable();
        }

        return $change;
    }
}

<?php declare (strict_types=1);

namespace App\VendingMachine\Application\Service;

use App\VendingMachine\Domain\Coin\CoinService;
use App\VendingMachine\Domain\Coin\Errors\CoinsChangeNotAvailable;
use App\VendingMachine\Domain\Coin\Errors\CoinsInsuficientAmount;
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

        $requiredChange = $this->checkAmount($product);
        $coinsToReturn = $this->checkChange($requiredChange);

        $this->coinService->store();
        $this->coinService->returnChange($coinsToReturn);

        $this->productService->decreaseQuantity($product);
    }

    private function checkAmount(Product $product): float
    {
        $availableCoins = $this->coinService->getAvailableCoins();
        $availableAmount = $this->coinService->getAmount($availableCoins);

        $change = $availableAmount - $product->price()->value();
        if ($change < 0) {
            throw new CoinsInsuficientAmount($availableAmount);
        }

        return $change;
    }

    private function checkChange(float $change): array
    {
        $obtainableCoins = $this->coinService->getObtainableCoins();

        if (!$this->coinService->canGiveChange($change, $obtainableCoins)) {
            throw new CoinsChangeNotAvailable();
        }

        return $this->coinService->calculateCoinsChange($change, $obtainableCoins);
    }
}

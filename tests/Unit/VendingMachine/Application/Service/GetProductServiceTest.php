<?php declare(strict_types=1);

namespace VendingMachine\Tests\Unit\Application\Service;

use App\VendingMachine\Application\Service\GetProductService;
use App\VendingMachine\Domain\Coin\CoinService;
use App\VendingMachine\Domain\Coin\Errors\CoinsChangeNotAvailable;
use App\VendingMachine\Domain\Coin\Errors\CoinsInsuficientAmount;
use App\VendingMachine\Domain\Product\Enum\ProductsEnum;
use App\VendingMachine\Domain\Product\Errors\ProductNotAvailable;
use App\VendingMachine\Domain\Product\Product;
use App\VendingMachine\Domain\Product\ProductService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GetProductServiceTest extends TestCase
{
    private GetProductService $service;
    private MockObject&ProductService $productService;
    private MockObject&CoinService $coinService;

    protected function setUp(): void
    {
        $this->productService = $this->createMock(ProductService::class);
        $this->coinService = $this->createMock(CoinService::class);
        $this->service = new GetProductService($this->productService, $this->coinService);
    }

    public function test_it_executes_successfully(): void
    {
        $product = Product::build(66, ProductsEnum::SODA->value, 1.50, 1);

        $this->productService->method('get')->willReturn($product);
        $this->coinService->method('getAvailableCoins')->willReturn(['1.00' => 2]);
        $this->coinService->method('getAmount')->willReturn(2.00);
        $this->coinService->method('canGiveChange')->willReturn(true);
        $this->coinService->method('calculateCoinsChange')->willReturn(['0.50' => 1]);

        $this->coinService->expects($this->once())->method('store');
        $this->coinService->expects($this->once())->method('returnChange')->with(['0.50' => 1]);
        $this->productService->expects($this->once())->method('decreaseQuantity')->with($product);

        $this->service->execute(ProductsEnum::SODA->value);
    }

    public function test_it_throws_exception_if_product_not_available(): void
    {
        $product = Product::build(66, ProductsEnum::SODA->value, 1.50, 0);
        $this->productService->method('get')->willReturn($product);

        $this->expectException(ProductNotAvailable::class);
        $this->service->execute(ProductsEnum::SODA->value);
    }

    public function test_it_throws_exception_if_insufficient_funds(): void
    {
        $product = Product::build(99, ProductsEnum::SODA->value, 1.50, 1);

        $this->productService->method('get')->willReturn($product);
        $this->coinService->method('getAvailableCoins')->willReturn(['1.00' => 1]);
        $this->coinService->method('getAmount')->willReturn(1.00);

        $this->expectException(CoinsInsuficientAmount::class);
        $this->service->execute(ProductsEnum::SODA->value);
    }

    public function test_it_throws_exception_if_cannot_give_change(): void
    {
        $product = Product::build(99, ProductsEnum::SODA->value, 1.50, 1);

        $this->productService->method('get')->willReturn($product);
        $this->coinService->method('getAvailableCoins')->willReturn(['2.00' => 1]);
        $this->coinService->method('getAmount')->willReturn(2.00);
        $this->coinService->method('canGiveChange')->willReturn(false);

        $this->expectException(CoinsChangeNotAvailable::class);
        $this->service->execute(ProductsEnum::SODA->value);
    }
}

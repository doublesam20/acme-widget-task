<?php

namespace AcmeWidget\Strategy;

use AcmeWidget\Basket;
use AcmeWidget\Product;
use AcmeWidget\ProductCatalogue;
use AcmeWidget\DeliveryRule;
use PHPUnit\Framework\TestCase;

class BuyOneGetSecondHalfPriceStrategyTest extends TestCase
{
    private FixedDeliveryChargeStrategy $deliveryChargeStrategy;
    private ProductCatalogue $productCatalogue;
    private BuyOneGetSecondHalfPriceStrategy $offerStrategy;
    
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Initialize the FixedDeliveryChargeStrategy with delivery rules
        $this->deliveryChargeStrategy = new FixedDeliveryChargeStrategy([
            new DeliveryRule(90, 0.00),
            new DeliveryRule(50, 2.95),
            new DeliveryRule(0, 4.95),
        ]);

        // Initialize the ProductCatalogue
        $this->productCatalogue = new ProductCatalogue();
        $this->productCatalogue->addProduct(new Product('B01', 'Blue Widget', 7.95));
        $this->productCatalogue->addProduct(new Product('G01', 'Green Widget', 24.95));
        $this->productCatalogue->addProduct(new Product('R01', 'Red Widget', 32.95));

        // Initialize the BuyOneGetSecondHalfPriceStrategy offer strategy
        $this->offerStrategy = new BuyOneGetSecondHalfPriceStrategy();
    }
    
    /**
     * @return void
     */
    public function testApplyOffer(): void
    {
        // Create a basket with the BuyOneGetSecondHalfPriceStrategy
        $basket = new Basket(
            $this->deliveryChargeStrategy,
            [$this->offerStrategy], // Pass the offer strategy here
            $this->productCatalogue
        );

        // Add products to the basket
        $basket->add('R01');
        $basket->add('R01');

        // Ensure the total is calculated correctly with the offer applied
        $this->assertEqualsWithDelta(54.37, $basket->getTotal(), 0.01, ''); // Allow a small delta for floating-point comparison
    }
}

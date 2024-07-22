<?php

namespace AcmeWidget;

use AcmeWidget\Strategy\FixedDeliveryChargeStrategy;
use AcmeWidget\Strategy\BuyOneGetSecondHalfPriceStrategy;
use PHPUnit\Framework\TestCase;

class BasketTest extends TestCase
{
    private Basket $basket;
    private ProductCatalogue $productCatalogue;
    private FixedDeliveryChargeStrategy $deliveryChargeStrategy;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Initialize the ProductCatalogue
        $this->productCatalogue = new ProductCatalogue();
        $this->productCatalogue->addProduct(new Product('B01', 'Blue Widget', 7.95));
        $this->productCatalogue->addProduct(new Product('G01', 'Green Widget', 24.95));
        $this->productCatalogue->addProduct(new Product('R01', 'Red Widget', 32.95));
        
        // Initialize the FixedDeliveryChargeStrategy
        $this->deliveryChargeStrategy = new FixedDeliveryChargeStrategy([
            new DeliveryRule(90, 0.00),
            new DeliveryRule(50, 2.95),
            new DeliveryRule(0, 4.95),
        ]);
        
        // Initialize the Basket
        $this->basket = new Basket(
            $this->deliveryChargeStrategy,
            [new BuyOneGetSecondHalfPriceStrategy()],
            $this->productCatalogue
        );
    }
    
    public function testBasketWithGreenAndBlueWidgets(): void
    {
        $this->basket->add('B01'); // Blue Widget
        $this->basket->add('G01'); // Green Widget
        
        $this->assertEqualsWithDelta(37.85, $this->basket->getTotal(), 0.01, 'Total should be close to 37.85');
    }
    
    public function testBasketWithRedWidgets(): void
    {
        $this->basket->add('R01'); // Red Widget
        $this->basket->add('R01'); // Red Widget
        
        $this->assertEqualsWithDelta(54.37, $this->basket->getTotal(), 0.01, 'Total should be close to 54.37');
    }
    
    public function testBasketWithRedAndGreenWidgets(): void
    {
        $this->basket->add('R01'); // Red Widget
        $this->basket->add('G01'); // Green Widget
        
        $this->assertEqualsWithDelta(60.85, $this->basket->getTotal(), 0.01, 'Total should be close to 60.85');
    }
    
    public function testBasketWithMixedItems(): void
    {
        $this->basket->add('B01'); // Blue Widget
        $this->basket->add('B01'); // Blue Widget
        $this->basket->add('R01'); // Red Widget
        $this->basket->add('R01'); // Red Widget
        $this->basket->add('R01'); // Red Widget
        
        $this->assertEqualsWithDelta(98.27, $this->basket->getTotal(), 0.01, 'Total should be close to 98.27');
    }
}

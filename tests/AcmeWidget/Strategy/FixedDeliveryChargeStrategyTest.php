<?php

namespace AcmeWidget\Strategy;

use AcmeWidget\Basket;
use AcmeWidget\Product;
use AcmeWidget\ProductCatalogue;
use AcmeWidget\DeliveryRule;
use PHPUnit\Framework\TestCase;

class FixedDeliveryChargeStrategyTest extends TestCase
{
    private FixedDeliveryChargeStrategy $strategy;
    private ProductCatalogue $productCatalogue;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Initialize the FixedDeliveryChargeStrategy with delivery rules
        $this->strategy = new FixedDeliveryChargeStrategy([
            new DeliveryRule(90, 0.00),
            new DeliveryRule(50, 2.95),
            new DeliveryRule(0, 4.95),
        ]);
        
        // Initialize the ProductCatalogue
        $this->productCatalogue = new ProductCatalogue();
        $this->productCatalogue->addProduct(new Product('B01', 'Blue Widget', 15.00));
        $this->productCatalogue->addProduct(new Product('G01', 'Green Widget', 22.85));
    }
    
    public function testCalculateDeliveryCharge(): void
    {
        // Create a basket with no offer strategies
        $basket = new Basket(
            $this->strategy,
            [], // No offer strategies
            $this->productCatalogue
        );
        
        // Add products to the basket
        $basket->add('B01');
        $basket->add('G01');
        
        // Calculate the total without delivery charge
        $totalWithoutDelivery = $basket->getTotalWithoutDelivery();
        $this->assertEqualsWithDelta(37.85, $totalWithoutDelivery, 0.01, 'Total without delivery charge should be 37.85');

        // Ensure the correct charge is applied
        $this->assertEqualsWithDelta(4.95, $this->strategy->calculate($basket), 0.01, 'Delivery charge should be 2.95');
    }
}

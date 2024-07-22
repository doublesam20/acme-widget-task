<?php

use PHPUnit\Framework\TestCase;
use AcmeWidget\{Product, DeliveryRule, Basket, ProductCatalogue};
use AcmeWidget\Strategy\{FixedDeliveryChargeStrategy, BuyOneGetSecondHalfPriceStrategy};

class IntegrationTest extends TestCase
{
    private ProductCatalogue $productCatalogue;
    private FixedDeliveryChargeStrategy $deliveryStrategy;
    private BuyOneGetSecondHalfPriceStrategy $offerStrategy;
    
    /**
     * @return void
     */
    protected function setUp(): void
    {
        // Initialize the ProductCatalogue
        $this->productCatalogue = new ProductCatalogue();
        $this->productCatalogue->addProduct(new Product('B01', 'Blue Widget', 7.95));
        $this->productCatalogue->addProduct(new Product('G01', 'Green Widget', 24.95));
        $this->productCatalogue->addProduct(new Product('R01', 'Red Widget', 32.95));
        
        $rules = [
            new DeliveryRule(90, 0),
            new DeliveryRule(50, 2.95),
            new DeliveryRule(0, 4.95),
        ];
        
        $this->deliveryStrategy = new FixedDeliveryChargeStrategy($rules);
        $this->offerStrategy = new BuyOneGetSecondHalfPriceStrategy();
    }
    
    /**
     * @return void
     */
    public function testBasketTotalWithoutOffers(): void
    {
        $basket = new Basket($this->deliveryStrategy, [], $this->productCatalogue);
        $basket->add('B01');
        $basket->add('G01');
        
        $this->assertEqualsWithDelta(37.85, $basket->getTotal(), 0.01, 'Total should be close to 37.85');
    }
    
    /**
     * @return void
     */
    public function testBasketTotalWithOfferOnRedWidget(): void
    {
        $basket = new Basket($this->deliveryStrategy, [$this->offerStrategy], $this->productCatalogue);
        $basket->add('R01');
        $basket->add('R01');
        
        $this->assertEqualsWithDelta(54.37, $basket->getTotal(), 0.01, 'Total should be close to 54.37');
    }
    
    /**
     * @return void
     */
    public function testBasketTotalWithMixedProducts(): void
    {
        $basket = new Basket($this->deliveryStrategy, [$this->offerStrategy], $this->productCatalogue);
        $basket->add('R01');
        $basket->add('G01');
        
        $this->assertEqualsWithDelta(60.85, $basket->getTotal(), 0.01, 'Total should be close to 60.85');
    }
    
    /**
     * @return void
     */
    public function testBasketTotalWithMultipleProductsAndOffers(): void
    {
        $basket = new Basket($this->deliveryStrategy, [$this->offerStrategy], $this->productCatalogue);
        $basket->add('B01');
        $basket->add('B01');
        $basket->add('R01');
        $basket->add('R01');
        $basket->add('R01');
        
        $this->assertEqualsWithDelta(98.27, $basket->getTotal(), 0.01, 'Total should be close to 98.27');
    }
    
    /**
     * @return void
     */
    public function testBasketTotalWithNoDeliveryCharge(): void
    {
        $basket = new Basket($this->deliveryStrategy, [$this->offerStrategy], $this->productCatalogue);
        $basket->add('R01');
        $basket->add('R01');
        $basket->add('R01');
        $basket->add('G01');
        $basket->add('B01');
        
        $this->assertEqualsWithDelta(115.27, $basket->getTotal(), 0.01, 'Total should be close to 115.27');
    }
    
    /**
     * @return void
     */
    public function testBasketWithOnlyRedWidgets(): void
    {
        $basket = new Basket($this->deliveryStrategy, [$this->offerStrategy], $this->productCatalogue);
        $basket->add('R01');
        $basket->add('R01');
        $basket->add('R01');
        $basket->add('R01');
        
        $this->assertEqualsWithDelta(98.85, $basket->getTotal(), 0.01, 'Total should be close to 98.85');
    }
}

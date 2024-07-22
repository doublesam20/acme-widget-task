<?php

namespace AcmeWidget;

use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    private Product $product;
    
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->product = new Product('R01', 'Red Widget', 32.95);
    }
    
    /**
     * @return void
     */
    public function testProductCode(): void
    {
        $this->assertEquals('R01', $this->product->getCode());
    }
    
    /**
     * @return void
     */
    public function testProductName(): void
    {
        $this->assertEquals('Red Widget', $this->product->getName());
    }
    
    /**
     * @return void
     */
    public function testProductPrice(): void
    {
        $this->assertEqualsWithDelta(32.95, $this->product->getPrice(), 0.01, ''); // Allowing small delta for floating-point comparison
    }
}

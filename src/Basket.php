<?php

namespace AcmeWidget;

use AcmeWidget\Strategy\DeliveryChargeStrategyInterface;
use AcmeWidget\Strategy\SpecialOfferStrategyInterface;

class Basket
{
    /** @var Product[] */
    private array $products = [];
    
    /** @var float[] */
    private array $discounts = [];
    
    private DeliveryChargeStrategyInterface $deliveryChargeStrategy;
    
    /** @var SpecialOfferStrategyInterface[] */
    private array $offerStrategies;
    
    private ProductCatalogue $productCatalogue;
    
    /**
     * @param DeliveryChargeStrategyInterface $deliveryChargeStrategy
     * @param SpecialOfferStrategyInterface[] $offerStrategies
     * @param ProductCatalogue $productCatalogue
     */
    public function __construct(
        DeliveryChargeStrategyInterface $deliveryChargeStrategy,
        array $offerStrategies,
        ProductCatalogue $productCatalogue
    ) {
        $this->deliveryChargeStrategy = $deliveryChargeStrategy;
        $this->offerStrategies = $offerStrategies;
        $this->productCatalogue = $productCatalogue;
    }
    
    /**
     * @param string $productCode
     * @return void
     */
    public function add(string $productCode): void
    {
        $product = $this->productCatalogue->getProduct($productCode);
        if ($product !== null) {
            $this->products[] = $product;
        }
    }
    
    /**
     * @return float
     */
    public function getTotal(): float
    {
        foreach ($this->offerStrategies as $strategy) {
            $strategy->apply($this);
        }
        
        $total = array_reduce($this->products, fn($carry, $product) => $carry + $product->getPrice(), 0);
        $total -= array_sum($this->discounts);
        $total += $this->deliveryChargeStrategy->calculate($this);
        
        return $total;
    }
    
    /** @return Product[] */
    public function getProducts(): array
    {
        return $this->products;
    }
    
    /**
     * @return float
     */
    public function getTotalWithoutDelivery(): float
    {
        $total = array_reduce($this->products, fn($carry, $product) => $carry + $product->getPrice(), 0);
        $total -= array_sum($this->discounts);
        // Add a debug statement
        error_log('Basket total without delivery: ' . $total);
        return $total;
    }
    
    
    /**
     * @param float $discount
     * @return void
     */
    public function addDiscount(float $discount): void
    {
        $this->discounts[] = $discount;
    }
}

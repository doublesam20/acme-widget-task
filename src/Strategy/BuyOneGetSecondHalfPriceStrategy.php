<?php

namespace AcmeWidget\Strategy;

use AcmeWidget\Basket;

class BuyOneGetSecondHalfPriceStrategy implements SpecialOfferStrategyInterface
{
    public function apply(Basket $basket): void
    {
        $products = $basket->getProducts();
        $count = 0;
        
        foreach ($products as $product) {
            if ($product->getCode() === 'R01') {
                $count++;
                if ($count % 2 === 0) {
                    $basket->addDiscount($product->getPrice() / 2);
                }
            }
        }
    }
}

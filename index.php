<?php
require 'vendor/autoload.php';

use AcmeWidget\Product;
use AcmeWidget\DeliveryRule;
use AcmeWidget\Basket;
use AcmeWidget\ProductCatalogue;
use AcmeWidget\Strategy\FixedDeliveryChargeStrategy;
use AcmeWidget\Strategy\BuyOneGetSecondHalfPriceStrategy;

// Initialize product catalog
$productCatalogue = new ProductCatalogue();
$productCatalogue->addProduct(new Product('B01', 'Blue Widget', 7.95));
$productCatalogue->addProduct(new Product('G01', 'Green Widget', 24.95));
$productCatalogue->addProduct(new Product('R01', 'Red Widget', 32.95));

// Initialize delivery charge calculator
$rules = [
    new DeliveryRule(90, 0),
    new DeliveryRule(50, 2.95),
    new DeliveryRule(0, 4.95),
];

// Initialize offers & delivery strategy
$deliveryStrategy = new FixedDeliveryChargeStrategy($rules);
$offerStrategy = new BuyOneGetSecondHalfPriceStrategy();

// Initialize basket
$basket = new Basket($deliveryStrategy, [$offerStrategy], $productCatalogue);

// Add products to basket
$basket->add('R01');
$basket->add('R01');

// Get total cost
echo $basket->getTotal(); // Should output 54.37

# Acme Widget Co Sales System

This is a proof of concept for Acme Widget Co's new sales system. The application allows customers to add products to a basket, apply special offers, and calculate the total cost including delivery charges.

## Features
- Add products to a basket
- Apply special offers (e.g., buy one red widget, get the second half price)
- Calculate delivery charges based on the total order amount

## Requirements
- PHP 8.2+
- Composer
- Docker
- Docker Compose

## Setup

1. **Clone the repository**:
    ```sh
    git https://github.com/doublesam20/acme-widget-task.git
    cd acme-widget-task
    ```

2. **Install dependencies**:
    ```sh
    composer install
    ```

3. **Build and run Docker containers**:
    ```sh
    docker-compose up -d
    ```

# Usage
```php
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

````



## Running Tests

To run unit and integration tests using PHPUnit, execute:
```sh
docker-compose exec app vendor/bin/phpunit
```

## Static Analysis with PHPStan

To analyze the codebase with PHPStan and find potential bugs, execute:
```sh
docker-compose exec app vendor/bin/phpstan analyse


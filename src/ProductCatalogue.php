<?php

namespace AcmeWidget;

class ProductCatalogue
{
    /** @var Product[] */
    private array $products = [];
    
    /**
     * Adds a product to the catalogue.
     *
     * @param Product $product
     * @return void
     */
    public function addProduct(Product $product): void
    {
        $this->products[$product->getCode()] = $product;
    }
    
    /**
     * Retrieves a product by its code.
     *
     * @param string $code
     * @return Product|null
     */
    public function getProduct(string $code): ?Product
    {
        return $this->products[$code] ?? null;
    }
}

<?php

namespace AcmeWidget\Strategy;

use AcmeWidget\Basket;

interface SpecialOfferStrategyInterface
{
    public function apply(Basket $basket): void;
}

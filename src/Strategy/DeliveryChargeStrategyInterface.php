<?php

namespace AcmeWidget\Strategy;

use AcmeWidget\Basket;

interface DeliveryChargeStrategyInterface
{
    public function calculate(Basket $basket): float;
}

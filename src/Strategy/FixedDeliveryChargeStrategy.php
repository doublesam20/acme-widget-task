<?php

namespace AcmeWidget\Strategy;

use AcmeWidget\Basket;
use AcmeWidget\DeliveryRule;

class FixedDeliveryChargeStrategy implements DeliveryChargeStrategyInterface
{
    /** @var DeliveryRule[] */
    private array $rules;

    /**
     * @param DeliveryRule[] $rules
     */
    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * @param Basket $basket
     * @return float
     */
    public function calculate(Basket $basket): float
    {
        $total = $basket->getTotalWithoutDelivery();

        foreach ($this->rules as $rule) {
            if ($total >= $rule->getMinAmount()) {
                return $rule->getCharge();
            }
        }
        
        // Ensure there's always a default rule
        $lastRule = end($this->rules);
        return $lastRule ? $lastRule->getCharge() : 0.00;
    }
}

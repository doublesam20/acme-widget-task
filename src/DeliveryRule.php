<?php

namespace AcmeWidget;

class DeliveryRule
{
    private float $minAmount;
    private float $charge;
    
    /**
     * @param float $minAmount
     * @param float $charge
     */
    public function __construct(float $minAmount, float $charge)
    {
        $this->minAmount = $minAmount;
        $this->charge = $charge;
    }
    
    /**
     * @return float
     */
    public function getMinAmount(): float
    {
        return $this->minAmount;
    }
    
    /**
     * @return float
     */
    public function getCharge(): float
    {
        return $this->charge;
    }
}

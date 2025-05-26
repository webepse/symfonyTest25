<?php

namespace App\Service;

class PriceCalculator
{
    private float $taxRate;
    private PromotionService $promotionService;

    public function __construct(float $taxRate, PromotionService $promotionService)
    {
        $this->taxRate = $taxRate;
        $this->promotionService = $promotionService;
    }

    /**
     * Permet de calculer un prix
     * @param float $basePrice
     * @param float $discourtPercent
     * @return float
     */
    public function calculate(float $basePrice, float $discourtPercent): float
    {
        // appliquer la remise
        $dicounted = $basePrice * (1 - $discourtPercent / 100);

        // appliquer la promotion éventuelle (service externe)
        $promoted = $this->promotionService->applyPromotion($dicounted);

        // ajouter la TVA
        $final = $promoted * (1 + $this->taxRate / 100);

        return round($final, 2);

    }
}
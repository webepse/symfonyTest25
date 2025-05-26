<?php

namespace App\tests\Service;

use App\Service\PriceCalculator;
use App\Service\PromotionService;
use PHPUnit\Framework\TestCase;

class PriceCalculatorTest extends TestCase
{
    public function testCalculatedWithDiscountAndTax()
    {
        // création du mock pour PromotionService
        $promotionService = $this->createMock(PromotionService::class);
        // on définit le comportement attendu du mock
        // on s'attend à ce qu'il renvoie le même prix sans réduction
        // on dit: "Si quelqu'un appelle applyPromotion(), rends juste le prix sans rien changer"
        /* function($price) {
           return $price;
       }
       fn($price) => $price;*/
        $promotionService->method('applyPromotion')
            ->willReturnCallback(fn($price) => $price); // pas de promo

        // TVA de 21%
        $calculator = new PriceCalculator(21.0, $promotionService);

        // exemple : prix de base 100€, remise de 10%
        $result = $calculator->calculate(100.0,10.0);
        // calcul attendu:
        // 100 - 10% = 90
        // +21% TVA => 90 * 1.21 = 108.9
        $this->assertEquals(108.9, $result);
    }

    public function testCalculatedWithPromotion()
    {
        $promotionService = $this->createMock(PromotionService::class);
        $promotionService->method('applyPromotion')
            ->willReturnCallback(fn($price) => $price - 5); // promo fixe de 5€

        $calculator = new PriceCalculator(21.0, $promotionService);
        $result = $calculator->calculate(100.0,10.0);

        // 100 - 10% = 90
        // -5 promo = 85
        // +21% de TVA = 102.85
        $this->assertEquals(102.85, $result);
    }
}
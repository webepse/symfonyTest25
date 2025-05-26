<?php

namespace App\Service;

use App\Entity\Product;

class PromotionService
{
    /**
     * Permet d'appliquer une promotion à un prix donné
     * @param float $price
     * @return float
     */
    public function applyPromotion(float $price)
    {
        // En prod, ce service consulterait la BDD ou un api
        return $price; // pas de promo par défaut
    }
}

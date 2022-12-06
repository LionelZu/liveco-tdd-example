<?php

namespace App\Service;

class CotisationService {

    function compute($adherent, $amount) {
        $baseCotisation = $adherent->getCotisation();
        if($amount > 10000) {
            return $baseCotisation * 0.8;
        } elseif($amount > 5000) {
            return $baseCotisation * 0.9;
        }
        return $baseCotisation;
    }
}